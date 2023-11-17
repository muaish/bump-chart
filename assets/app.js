import './styles/app.css';
import { BumpChart } from './bump-chart';

let bump_chart = new BumpChart();
const dataSelect = document.getElementById('dataSelect');

function initialize() {
  return new Promise((resolve, reject) => {
    if (bump_chart.initialized) {
      resolve();
    } else {
      fetchConfig()
        .then(settings => {
          bump_chart = new BumpChart({}, settings);
          bump_chart.initialized = true;
          resolve();
        })
        .catch(e => reject(e));
    }
  });
}

function fetchConfig() {
  return fetch('api/json')
    .then(r => r.json());
};

function fetchData(selectedFile) {
  const url = `api/data/load?selectedFile=${selectedFile}`
  return fetch(url)
    .then(r => r.json());

};

function changeDataSelect(targetValue, triggerChange = false) {
  if (dataSelect.value !== targetValue && dataSelect.querySelectorAll(`option[value = "${targetValue}"]`).length > 0) {
    dataSelect.querySelectorAll('option').forEach(x => {
      if (x.value === targetValue) {
        x.setAttribute('selected', 'selected');
      }
      else {
        x.removeAttribute('selected');
      }
    });
    if (triggerChange) {
      dataSelect.dispatchEvent(new Event('change'));
    }
  }
}

function HashChange(selectedFile) {
  const hash = window.location.hash.slice(1);
  if (hash !== dataSelect.value) {
    changeDataSelect(hash, false);
  }
  initialize()
    .then(() => {
      fetchData(hash)
        .then(d => {
          removeExistingChart();
          bump_chart.data = d;
          bump_chart.render();
        });
    });
}

function removeExistingChart() {
  const chart = document.getElementById('chart');
  const existingChart = chart.querySelector('svg')

  if (existingChart) {
    existingChart.remove();
  }
}

document.getElementById('dataSelect').addEventListener('change', function (event) {
  const selectedFile = dataSelect.value;
  window.location.href = '#' + selectedFile;
});

window.addEventListener('DOMContentLoaded', function () {
  const selectedFile = window.location.hash.slice(1);
  if (selectedFile) {
    changeDataSelect(selectedFile, false);
    window.dispatchEvent(new HashChangeEvent('hashchange'));
  }
});

window.addEventListener('hashchange', HashChange);