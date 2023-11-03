/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import { BumpChart } from './bump-chart';

// Fetch the updated JSON configuration file
fetch('api/json')
  .then(response => response.json())
  .then(updatedSettings => {

    const jsonData = updatedSettings;

    fetch('api/data/data-4')
      .then(r => r.json())
      .then(d => {

        let lineWidth = jsonData.line_width
        let background = jsonData.stages.label.background
        let alignEnds = jsonData.stages.label.align_ends
        let sizeVariable = jsonData.item.size
        let positionVariable = jsonData.item.label.position
        let marginVariable = jsonData.item.margin

        if(lineWidth === "" || lineWidth === undefined){
          lineWidth = 3;
        }

        if(background === "" || background === undefined){
          background = '#000';
        }

        if(alignEnds === "" || alignEnds === undefined){
          alignEnds = true;
        }

        if(sizeVariable === "" || sizeVariable === undefined ){
          sizeVariable = 30;
        }

        if(positionVariable === "" || positionVariable === undefined){
          positionVariable = 'end';
        }

        if(marginVariable === "" || marginVariable === undefined){
          marginVariable = 10;
        }

        console.log(jsonData)

        const bump_chart = new BumpChart(d, {
          line_width: lineWidth,
          stages: {
            label: {
              background: background,
              align_ends: alignEnds
            }
          },
          item: {
            size: sizeVariable,
            label: {
              position: positionVariable,
            },
            margin: marginVariable,
          }
        });
        console.log(bump_chart);
        bump_chart.render();
      });
  });
