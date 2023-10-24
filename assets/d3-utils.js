import * as d3 from 'd3';

export function circleNumber({r=20, s=2, sa='1 0', sc='#CCC', bg='#CCC', fg='#FFF', txt='1'}) {
  const _r = r - s/2;
  const svg = d3.create('svg');
  svg.append('circle')
  .attr('cx', r)
  .attr('cy', r)
  .attr('r', _r)
  .attr('stroke-dasharray', sa)
  .attr('stroke-width', s)
  .attr('stroke', sc)
  .attr('fill', bg);
  svg.append('text')
  .attr('font-size', 2*(r-s))
  .attr('fill', fg)
  .attr('text-anchor', 'middle')
  .attr('alignment-baseline', 'middle')
  .attr('x', r)
  .attr('y', r + 2*(r-s)*0.08)
  .text(txt);

  return svg;
}

export function sortByIndex({arrayObject=this, index=0, indexElement='data', dir='asc'}) {
  return arrayObject.sort((a,b) => dir === 'asc' ? (a[indexElement][index] - b[indexElement][index]) : (b[indexElement][index] - a[indexElement][index]));
};
