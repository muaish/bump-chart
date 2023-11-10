import './styles/app.css';
import { BumpChart } from './bump-chart';
import { forEach } from 'core-js/features/array';

let bump_chart = new BumpChart();

function fetchDataAndRender(selectedFile) {
  const formData = new FormData();
  formData.append('selectedFile', selectedFile);

  console.log(bump_chart.initialized);

  if (bump_chart.initialized) {
    fetch('api/data/load', {
      method: 'POST',
      body: formData,
    })
      .then(r => r.json())
      .then(d => {
        const existingGraph = document.getElementById('chart');
        if (existingGraph) {
          existingGraph.innerHTML = '';
        }
        bump_chart = new BumpChart(d);
        bump_chart.render();
      })
      .catch(error => {
        console.error('Error loading data:', error);
      });
  }
  else {
    fetch('api/json')
      .then(response => response.json())
      .then(updatedSettings => {

        bump_chart = new BumpChart(null, updatedSettings);
        bump_chart.initialized = true;

        fetch('api/data/load', {
          method: 'POST',
          body: formData,
        })
          .then(r => r.json())
          .then(d => {

            
            const existingGraph = document.getElementById('chart');
            if (existingGraph) {
              existingGraph.innerHTML = '';
            }
            bump_chart.data = d;
            bump_chart.render();
          })
          .catch(error => {
            console.error('Error loading data:', error);
          });
        
        //console.log(bump_chart);
      })
      .catch(error => {
        console.error('Error fetching JSON settings:', error);
      });
    bump_chart.initialized = true;
  }
}

document.getElementById('dataSelect').addEventListener('change', function (event) {
  const selectedFile = document.getElementById('dataSelect').value;
  fetchDataAndRender(selectedFile);
  window.location.href = '#' + selectedFile;
});

window.addEventListener('DOMContentLoaded', function () {
  const selectedFile = window.location.hash.slice(1);
  if (selectedFile) {
    const dataSelect = document.getElementById('dataSelect');
    const allOptions = dataSelect.querySelectorAll('option');

    allOptions.forEach((option) => {
      const optionName = option.value;

      if (optionName === selectedFile) {
        option.setAttribute('selected', true);
      } else {
        option.removeAttribute('selected');
      }
    });
    fetchDataAndRender(selectedFile);
  }
});

window.onhashchange = function () {
  location.reload();
};