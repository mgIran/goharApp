<?php
require (Yii::getPathOfAlias('ext').'/jpgraph/jpgraph.php');
require(Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_bar.php');
//require (Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_line.php');
//require (Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_scatter.php');
//require(Yii::getPathOfAlias('ext').'/jpgraph/jpgraph_regstat.php');

$successBars = $values['avg'];
$failedBars = $values['sum'];
$days = array(3,6,9,12);

$maxPrice = max($failedBars);

// Create the graph. These two calls are always required
$graph = new Graph($_POST['width']+200,200,'auto');
$graph->SetScale("linlin");

$graph->SetMargin(50,50,50,50);

$theme_class = new UniversalTheme;
$graph->SetTheme($theme_class);

$maxPrice += ($maxPrice % 10000);
$ticks = array(0);
$ticksMin = array(0,50000);
$i = 1;
while(end($ticks) <= $maxPrice){
    $ticks[] = 100000 * $i;
    $ticksMin[] = ((100000 * $i) + 50000);
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
@unlink('test2.png');
$graph->Stroke('test2.png');

?>