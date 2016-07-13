<?php
require (Yii::getPathOfAlias('ext').'/jpgraph/jpgraph.php');
require(Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_bar.php');
//require (Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_line.php');
//require (Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_scatter.php');
//require(Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_regstat.php');

$successBars = $values['avg'];
$failedBars = $values['sum'];
$days = array(7,15,30,45,60);

$maxPrice = max($failedBars);

// Create the graph. These two calls are always required
$graph = new Graph($_POST['width'],200,'auto');
$graph->SetScale("linlin");

$graph->SetMargin(50,50,50,50);

$theme_class = new UniversalTheme;
$graph->SetTheme($theme_class);

$maxPrice += ($maxPrice % 1000);
$ticks = array(0);
$ticksMin = array(0,5000);
$i = 1;
while(end($ticks) <= $maxPrice){
    $ticks[] = 10000 * $i;
    $ticksMin[] = ((10000 * $i) + 5000);
    $i++;
}
unset($ticksMin[$i]);


$graph->yaxis->SetTickPositions($ticks, $ticksMin);

$graph->SetBox(false);

$graph->ygrid->SetFill(false);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

// Setup month as labels on the X-axis
$graph->xaxis->SetTickLabels($days);

// Create the bar plots
$successBars = new BarPlot($successBars);
$failedBars = new BarPlot($failedBars);
//$average = new LinePlot($average);


$pricesPlots = new GroupBarPlot(array($successBars,$failedBars));

// ...and add it to the graPH
$graph->Add($pricesPlots);

$successBars->SetColor('#78a300');
$successBars->SetFillColor("#78a300");

$failedBars->SetColor('#ec4444');
$failedBars->SetFillColor("#ec4444");
// Display the graph
@unlink('test.png');
$graph->Stroke('test.png');

?>