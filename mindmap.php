<?php
include('scripts.php');
dbconn();
session_start();

function colors($comp,$color_prio)
{
    if ($comp < 2000000000)
    {
        return "00FF00";
    }
    else if ($color_prio == 2)
    {
        return "EE0000";
    } 
    else if ($color_prio == 1)
    {
        return "FFA500";
    }
    else 
    {
        return "fff";
    }

}

$myfile = fopen("flare.json", "w") or die("Unable to open file!");
$txt = "";
$txt .= '{
    "name": "Demil",
    "children": [';
$res = mysql_query('select * from project order by prio');
$parent = "";
while ($line = mysql_fetch_assoc($res)){
    if ($parent == ""){
        $parent .= '{"name": "'.$line['description'].'",
            "children": [';
    } else {
        $parent .= ', {"name": "'.$line['description'].'",
            "children": [';
    }
    $child = "";
    $taskres = mysql_query('select * from task where project='.$line['id'].' order by comp desc,color_prio desc');
    while ($taskline = mysql_fetch_assoc($taskres)){
        if ($child == ""){
            $child .= '{"name": "'.$taskline['description'].'","fill": "'.  colors($taskline['comp'], $taskline['color_prio']).'"}';
        } else {
            $child .= ', {"name": "'.$taskline['description'].'","fill": "'.  colors($taskline['comp'], $taskline['color_prio']).'"}';
        }
    }
    $parent .= $child;
    $parent .= ']}';   
          
}
$txt .= $parent;
$txt .= ']}';
      

/*$txt .= '{
    "name": "Demil",
    "children": [{
	"name": "Ammunition Storage Containers",
        "children": [{
		"name": "Order Climate Loggers"
	},{	"name": "Run the Storage Containers closed and do a heat test."
	},{	"name": "Clean up and fasten loose components in the containers"	
	},{	"name": "Meet with Electrician on tuesday to sort out the electrical supply problem."
	},{	"name": "Methods of ducting splitting than can be used for the containers"
	},{	"name": "Set operating temperature for the air-conditioners to work at."
	},{	"name": "Update documentation on the storage containers"
	},{	"name": "Fasten fire extinguishers to the containers."
	},{	"name": "Run storage containers with door closed again on a warmer day."
	},{	"name": "Put up fireextiguisher sign as well as no fire sign. First go buy the signs"
	},{	"name": "Paint transportation covers"
	},{	"name": "Larger fuel filters to be added to tanks."
	},{	"name": "Maintain generator"
	},{	"name": "Add the external eleictrical supply capability."
	},{	"name": "Paint rheinmetall signs"
	},{	"name": "Setup Baseline for the project"
	},{	"name": "Final heat test in a heat wave"
	}]
}]
}';*/
fwrite($myfile, $txt);

fclose($myfile);

?>
<!DOCTYPE html>
<meta charset="utf-8">
<style>

.node circle {
  fill: #fff;
  stroke: black;
  stroke-width: 1.5px;
}

.node {
  font: 10px sans-serif;
}

.link {
  fill: none;
  stroke: #ccc;
  stroke-width: 1.5px;
}

</style>
<body>
    <script src="d3/d3.min.js"></script>
<script>

var width = 1500,
    height = 900;

var cluster = d3.layout.cluster()
    .size([height, width - 1000]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(40,0)");

d3.json("flare.json", function(error, root) {
  if (error) throw error;

  var nodes = cluster.nodes(root),
      links = cluster.links(nodes);

  var link = svg.selectAll(".link")
      .data(links)
    .enter().append("path")
      .attr("class", "link")
      .attr("d", diagonal);

  var node = svg.selectAll(".node")
      .data(nodes)
      .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })

  node.append("circle")
      .attr("r", 4.5)
      .style("fill",function(d) {return d.fill});

  node.append("text")
      .attr("dx", function(d) { return d.children ? -8 : 8; })
      .attr("dy", 3)
      .style("text-anchor", function(d) { return d.children ? "end" : "start"; })
      .text(function(d) { return d.name; });
});

d3.select(self.frameElement).style("height", height + "px");

</script>