import * as d3 from 'd3';
import { circleNumber, sortByIndex } from './d3-utils';

Array.prototype.sortByIndex = sortByIndex;
export class BumpChart {
  initialized = false;
  settings = {
    "selector": "#chart",
    "line_width": 2,
    "stages": {
      "spacing": 50,
      "label": {
        "background": "#000",
        "color": "#FFF"
      }
    },
    "item": {
      "size": 30,
      "label": {
        "position": "both",
        "gap": 10
      },
      "margin": 10,
    }
  };

  constructor(data, settings={}) {
    this.settings = Object.assign(this.settings, settings);
    this.data = data;
    console.log(this.settings, settings);
  }

  render = function() {
    const n_rows = this.data.items.length;

    const size = this.settings.item.size;
    const x_space = this.settings.stages.spacing;
    const y_space = this.settings.item.margin;
    const line_width = this.settings.line_width;
    const r = size / 2;
    const _r = r + line_width / 2;
    const font_size = size - line_width * 6;

    // since no interaction, just draw out the svg
    const container = d3.select(this.settings.selector);
    this.svg = container.append('svg');
      //.attr('width', 10)
      // .attr('height', n_rows * this.settings.item.size + (n_rows - 1) * this.settings.item.margin);
    const colors = this.data.color;
    const groups = {};
    this.data.items.sortByIndex({dir:'desc'}).forEach((x,i) => {
      if (typeof x.color === 'undefined') {
        x.color = colors.shift();
      }
      if (typeof groups[x.label] === 'undefined') {
        groups[x.label] = this.svg.append('g')
          .attr('stroke', x.color)
          .attr('fill', x.color)
          .attr('stroke-width', line_width)
          .attr('font-size', font_size)
          .attr('alignment-baseline', 'middle');
      }
    });

    let prev_stage = null;
    let next_stage = null;
    let current_stage = null;

    let start_x = 0;
    let max_rows = 0;
    this.data.stages.forEach((stage, si) => {
      let is_first = si === 0;
      let is_last = si === (this.data.stages.length - 1);
      if (stage.max > max_rows) {
        max_rows = stage.max;
      }
      if (current_stage === null) {
        current_stage = this.data.items.sortByIndex({index:si, dir:'desc'}).slice(0, stage.max);
      }
      if (next_stage === null && typeof this.data.stages[si + 1] !== 'undefined') {
        next_stage = this.data.items.sortByIndex({index:si + 1, dir:'desc'}).slice(0, this.data.stages[si].max);
      }

      // starting label:
      if (is_first && (this.settings.item.label.position === 'both' || this.settings.item.label.position === 'start')) {
        let mw = 0;
        const labels = [];
        current_stage.forEach((x, y) => {
          const g = groups[x.label];
          const cir_y = y * (y_space + size) + r;
          const text_y = cir_y + font_size * 0.35;
          const l = g.append('text')
            .attr('y', text_y)
            .attr('stroke-width', line_width * 0.25)
            .attr('text-anchor', 'right')
            .text(x.label);
          const w = l.node().getComputedTextLength();
          if (w > mw) {
            mw = w;
          }
          labels.push([l, w]);
        });
        labels.forEach(x => {
          const [l, w] = x;
          l.attr('x', mw - w);
          this.svg.attr('width', mw);
        });
        start_x = mw + this.settings.item.label.gap;
      }

      // bump chart: circle and line
      current_stage.forEach((x, y) => {
        let exists;
        if (!is_last) {
          exists = next_stage ? next_stage.map(y => y.label).indexOf(x.label) >= 0 : false;
        } else {
          exists = prev_stage ? prev_stage.map(y => y.label).indexOf(x.label) >= 0 : false;
        }
        const g = groups[x.label];
        const cir_x = start_x + _r;
        const cir_y = y * (y_space + size) + _r;
        const text_y = cir_y + font_size * 0.35;

        // line 1st, so the cirle is on top in terms of z-layer
        if (next_stage) {
          const y2 = next_stage.indexOf(x);
          if (y2 >= 0) {
            // console.log(y, y2);
            const x1 = start_x + size + line_width / 2;
            g.append('line')
              .attr('x1', x1)
              .attr('y1', cir_y)
              // .attr('y1', y * (size + y_space) + _r)
              .attr('x2', x1 + x_space + line_width / 2)
              .attr('y2', y2 * (size + y_space) + _r);

          }
        }
        const c = g.append('circle')
          .attr('cx', cir_x)
          .attr('cy', cir_y)
          .attr('r', r);
        const t = g.append('text')
          .attr('x', cir_x)
          .attr('y', text_y)
          .attr('stroke-width', line_width * 0.25)
          .attr('text-anchor', 'middle')
          .text(y + 1);
        if (exists) {
          c.attr('fill', x.color);
          t.attr('stroke', 'white')
            .attr('fill', 'white');
        } else {
          c.attr('fill', 'white')
            .attr('stroke-dasharray', '10 3');
          t.attr('fill', x.color);
        }

      });
      start_x += size  + (is_last ? 0 : x_space) ;

      // ending label:
      if (is_last && (this.settings.item.label.position === 'both' || this.settings.item.label.position === 'end')) {
        start_x += this.settings.item.label.gap;
        let mw = 0;
        current_stage.forEach((x, y) => {
          const g = groups[x.label];
          const cir_y = y * (y_space + size) + r;
          const text_y = cir_y + font_size * 0.35;
          const l = g.append('text')
            .attr('x', start_x)
            .attr('y', text_y)
            .attr('stroke-width', line_width * 0.25)
            .attr('text-anchor', 'left')
            .text(x.label);
          const w = l.node().getComputedTextLength();
          if (w > mw) {
            mw = w;
          }
        });
        start_x += mw;
      }
      
      prev_stage = current_stage;
      current_stage = next_stage;
      next_stage = null;

    });
    this.svg
    .attr('width', start_x)
    .attr('height', max_rows * this.settings.item.size + (max_rows - 1) * this.settings.item.margin + this.settings.line_width);

    return this.svg.node();
  }
};