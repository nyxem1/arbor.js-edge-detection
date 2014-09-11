<?php
/**
 * Created by PhpStorm.
 * User: Rahul
 * Date: 5/1/14
 * Time: 12:16 PM
 */
$connect=mysql_connect("localhost","root","mysql") or die("Error connecting!! Check connection parameters");
mysql_select_db("mydb") or die(mysql_error());
$query="SELECT TargetNode,SourceNode FROM ARBOR";
$results=mysql_query($query) or die(mysql_error());

?>
<!DOCTYPE HTML>
<title>Arbor</title>
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" type="text/javascript" src="arbor.js"></script>
    <script type="text/javascript" type="text/javascript" src="arbor-tween.js"></script>
    <script type="text/javascript" type="text/javascript" src="renderer.js"></script>
    <script type="text/javascript" type="text/javascript" src="graphics.js"></script>

    <script type="text/javascript" type="text/javascript" src="cytoscape.js"></script>
    <script type="text/javascript" type="text/javascript" src="cytoscape.min.js"></script>

    <script type="text/javascript" type="text/javascript" src="jquery.qtip.js"></script>
    <link rel="stylesheet" type="text/css" href="jquery.qtip.css">

</head>
<body>
<div id="cy" style="height: 600px; width: 780px;" >
    <div id="box"></div>
</div>
<script>

  <?php
  $a=array();
  $b=array();
   while($row=mysql_fetch_array($results)){
    extract($row);
    $a[]=$TargetNode;
    $b[]=$SourceNode;
    }
  ?>
  var a=<?php echo json_encode($a);?>;
  var b=<?php echo json_encode($b);?>


    window.onload = function() {
console.log(b[0])
        $("#cy").cytoscape({

            layout: {
                name: 'arbor',
                liveUpdate: true,
                maxSimulationTime: 8000,
                ungrabifyWhileSimulating: true,
                gravity: true,
                fit: true,
                stiffness: 80,
                repulsion: 10000

            },
            showOverlay: false,
            zoomEnabled: false,
            panEnabled: false,
            boxSelectionEnabled: false,

            style: cytoscape.stylesheet()

                // ALL NODE STYLES
                .selector('node')
                .css({
                    'content': 'data(name)',
                    'background-color': 'data(faveColor)',
                    'color': 'data(faveFontColor)',
                    'text-valign': 'center',
                    // 'text-opacity': 0.8,
                    'font-weight': 'bold',
                    'font-size': 12
                })
                .selector(":active")
                .css({
                    "overlay-color": "#ffffff",
                    "overlay-padding": 0,
                    "overlay-opacity": 0
                }),

            elements: {
                nodes: [ { data : { shape:"dot", id: b[0], faveBorderColor: "#AAAAAA", name: b[0], faveColor: "#EEB211", faveFontColor: "#ffffff" } },
                    { data : { id: a[0], name: a[0], faveColor: "#21526a", faveFontColor: "#fff"} },
                    { data : { id: a[1], name: a[1], faveColor: "#21526a", faveFontColor: "#fff"} },
                    { data : { id: a[2], name: a[2], faveColor: "#21526a", faveFontColor: "#fff"} },
                    { data : { id: a[3], name: a[3], faveColor: "#21526a", faveFontColor: "#fff"} },
                    { data : { id: a[4], name: a[4], faveColor: "#21526a", faveFontColor: "#fff"} }
                ], //end "nodes"

                edges: [
                    { data : { target: a[0], source : b[0] } },
                    { data : { target: a[1], source : b[0]} },
                    { data : { target: a[2], source : b[0]} },
                    { data : { target: a[3], source : b[0]} },
                    { data : { target: a[4], source : b[0]} }
                ]//end "edges"
            },//end "elements"

            ready: function() {

                window.cy = this;
                cy.panningEnabled(false);

                // add a listener for edges are hovered
                cy.on('tap', 'node', function(event){
                    handle_click_node(event);
                });
                cy.on('mousemove','edge', function(event){
                    var target = event.cyTarget;
                    var sourceName = target.data("source");
                    var targetName = target.data("target");
                    var x=event.cyPosition.x;
                    var y=event.cyPosition.y;



                    var menutext = sourceName;

                    $("#box").qtip({
                        content: {
                            title:"Target Node "+ targetName,
                            text: "Connected to "+ menutext
                        },
                        show: {
                            delay: 0,
                            event: false,
                            ready: true,
                            effect:false

                        },
                        position: {
                            my: 'bottom center',
                            at: 'top center',

                            target: [x+3, y+3],
                            adjust: {
                                x: 7,
                                y:7

                            }

                        },
                        hide: {
                            fixed: true,
                            event: false,
                            inactive: 2000

                        },


                        style: {
                            classes: 'ui-tooltip-shadow ui-tooltip-youtube',

                            tip:
                            {
                                corner: true,
                                width: 24,         // resize the caret
                                height: 24         // resize the caret
                            }

                        }
                    });

                })




            }//end "ready"

        });//end $("#cy").cytoscape


    }//end "window.onload"

</script>
</body>
</html>
