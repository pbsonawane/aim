
var margin = {top: 20, right: 120, bottom: 20, left: 120},
    width = 960 - margin.right - margin.left,
    height = 800 - margin.top - margin.bottom;

var i = 0,
    duration = 750,
    root;

var tree = d3.layout.tree()
    .size([height, width]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });
//var topologyid = "#topologytree";
var svg;
function initd3(topologyid,jsondata)
{
	var w = '100%';//width + margin.right + margin.left;
	svg = d3.select(topologyid).append("svg")
		.attr("width", w)
		.attr("height", height + margin.top + margin.bottom)
	  .append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	inittree(jsondata)	
}
function inittree(flare)
{
	root= JSON.parse(flare);

  //root = flare;
  root.x0 = height / 2;
  root.y0 = 0;

  

  root.children.forEach(collapse);
  update(root);
}
function collapse(d) {
	if (d.children) {
	  d._children = d.children;
	  d._children.forEach(collapse);
	  d.children = null;
	}
  }
d3.select(self.frameElement).style("height", "800px");
var opencolor = "#fff";
var closecolor = "lightsteelblue";
var selectedcolor = "#FF0000";
var ignorecolor = "#f7cc0c";
function update( source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
      links = tree.links(nodes);

  // Normalize for fixed-depth.
  nodes.forEach(function(d) { d.y = d.depth * 180; });

  // Update the nodes…
  var node = svg.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter any new nodes at the parent's previous position.
  var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d,svg) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
      .on("click", click);

  nodeEnter.append("circle")
      .attr("r", 1e-6)
      .style("fill", function(d) { 
		if(selectedhost.indexOf(d.value) > -1)
		{
			return d._children ? selectedcolor : selectedcolor; 
		}
		else if(ignorehost.indexOf(d.value) > -1)
		{
			return d._children ? ignorecolor : ignorecolor; 
		}
		else
		{
			return d._children ? closecolor : opencolor; 
		}
	  });

  nodeEnter.append("text")
      .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.name; })
      .style("fill-opacity", 1e-6);

  // Transition nodes to their new position.
  var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

  nodeUpdate.select("circle")
      .attr("r", 5.5)
      .style("fill", function(d) {
		if(selectedhost.indexOf(d.value) > -1)
		{
			return d._children ? selectedcolor : selectedcolor; 
		}
		else if(ignorehost.indexOf(d.value) > -1)
		{
			return d._children ? ignorecolor : ignorecolor; 
		}
		else
		{
			return d._children ? closecolor : opencolor; 
		}
	  });

  nodeUpdate.select("text")
      .style("fill-opacity", 1);

  // Transition exiting nodes to the parent's new position.
  var nodeExit = node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .remove();

  nodeExit.select("circle")
      .attr("r", 1e-6);

  nodeExit.select("text")
      .style("fill-opacity", 1e-6);

  // Update the links…
  var link = svg.selectAll("path.link")
      .data(links, function(d) { return d.target.id; });

  // Enter any new links at the parent's previous position.
  link.enter().insert("path", "g")
      .attr("class", "link")
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      });

  // Transition links to their new position.
  link.transition()
      .duration(duration)
      .attr("d", diagonal);

  // Transition exiting nodes to the parent's new position.
  link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
      })
      .remove();

  // Stash the old positions for transition.
  nodes.forEach(function(d) {
    d.x0 = d.x;
    d.y0 = d.y;
  });
}
var ctrl = false;
var shift = false;
var selectedhost = [];
var ignorehost = [];
$(document).on("mousedown", function (e) {
	if(e.ctrlKey)
		ctrl = true;
	else
		ctrl = false;
	if(e.shiftKey)
		shift = true;
	else
		shift = false;	
});

// Toggle children on click.
function click(d,svg) {
	
  if (d.children) {
	if(ctrl)
	{
		if(selectedhost.indexOf(d.value) > -1)
		{
			selectedhost.splice(selectedhost.indexOf(d.value), 1);
		}
		else
		{
			selectedhost.push(d.value); 
		}
		ctrl = false;
		if(ignorehost.indexOf(d.value) > -1)
		{
			ignorehost.splice(ignorehost.indexOf(d.value), 1);
		}
	}
	else if(shift)
	{
		if(ignorehost.indexOf(d.value) > -1)
		{
			ignorehost.splice(ignorehost.indexOf(d.value), 1);
		}
		else
		{
			ignorehost.push(d.value); 
		}
		shift = false;
		if(selectedhost.indexOf(d.value) > -1)
		{
			selectedhost.splice(selectedhost.indexOf(d.value), 1);
		}
	}	
	else
	{
		d._children = d.children;
		d.children = null;
	}
    
  } else {
	if(ctrl)
	{
		if(selectedhost.indexOf(d.value) > -1)
		{
			selectedhost.splice(selectedhost.indexOf(d.value), 1);
		}
		else
		{
			selectedhost.push(d.value); 
		}
		ctrl = false;
		if(ignorehost.indexOf(d.value) > -1)
		{
			ignorehost.splice(ignorehost.indexOf(d.value), 1);
		}
	}
	else if(shift)
	{
		if(ignorehost.indexOf(d.value) > -1)
		{
			ignorehost.splice(ignorehost.indexOf(d.value), 1);
		}
		else
		{
			ignorehost.push(d.value); 
		}
		shift = false;
		if(selectedhost.indexOf(d.value) > -1)
		{
			selectedhost.splice(selectedhost.indexOf(d.value), 1);
		}
	}	
	else
	{
		d.children = d._children;
		d._children = null;
	}
  }
  update(d);
}
function expand(d){   
    var children = (d.children)?d.children:d._children;
    if (d._children) {        
        d.children = d._children;
        d._children = null;       
    }
    if(children)
      children.forEach(expand);
}

function expandAll(){
    expand(root); 
    update(root);
}

function collapseAll(){
    root.children.forEach(collapse);
    collapse(root);
    update(root);
}