<html>
<head>
  <title>goxp.org - leaderboard tracking for pokemon go</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <style>
    html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
    span {font-weight:bold;font-size:1.2em}
    body {min-width:600px}
    .inline { display:inline-block; width:80px }
    .result { font-weight:bold; font-size:1.1em; }
    .w3-table-all { min-width:525px; }
    #formpanel { display:none }
    #resultspanel {display:none}
  </style>
  <script type="text/JavaScript" src="randomsim.js"></script>
</head>
<body>
  <div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
    <span class="w3-bar-item w3-right">goxp.org - leaderboard tracking for pokemon go</span>
  </div>

  <!-- Sidebar/menu -->
  <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
    <div class="w3-container w3-row">
      <div class="w3-col s8 w3-bar">
      </div>
    </div>
    <div class="w3-bar-block">
      <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
      <a href="#" onclick="hideForms();" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fas fa-info-circle"></i>  Overview</a>
      <a href="#" onclick="showAverageForm();" class="w3-bar-item w3-button w3-padding"><i class="fas fa-grin-stars "></i>  Average # Shinies</a>
      <a href="#" onclick="showEncounterForm();" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye"></i>  Average # Encounters</a>
    </div>
  </nav>


  <!-- Overlay effect when opening sidebar on small screens -->
  <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

  <!-- !PAGE CONTENT! -->
  <div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Header -->
    <header class="w3-container" style="padding-top:22px">
      <h1>Shiny Probability Simulator</h1>
    </header>

    <div class="w3-row-padding w3-margin-bottom">
      <div class="w3-half">
        <div class="w3-container w3-red w3-padding-16" style="opacity:0.75">
          <div class="w3-left"><i class="fas fa-grin-stars w3-xxxlarge"></i></div>
          <div class="w3-right">
            <h3></h3>
          </div>
          <div class="w3-clear"></div>
          <h4>Average # of Shinies</h4>
          Simulate how many shinies people find over a specified
          number of encounters.
        </div>
      </div>
      <div class="w3-half">
        <div class="w3-container w3-blue w3-padding-16" style="opacity:0.75">
          <div class="w3-left"><i class="fa fa-eye w3-xxxlarge"></i></div>
          <div class="w3-right">
            <h3></h3>
          </div>
          <div class="w3-clear"></div>
          <h4>Average # of Encounters</h4>
          Simulate how many encounters it takes to find a specified
          number of shinies.
        </div>
      </div>

    <div class="w3-panel">
      <h3>Overview</h3>
      <p>
        This website uses Javascript to run repeated simulations to demonstrate how randomness
        works with regards to encountering shiny pokemon in pokemon go. It operates
        in one of two modes, depending on which goal you have in mind:
      </p>
      <ul>
        <li><b>Average number of shinies</b> - simulates how many shinies people find over a specified
        number of encounters. <a href="#" onclick="showAverageForm();">Click the red panel above or this link to run in this mode</a></li>
        <li><b>Average number of encounters</b> - simulates how many encounters it takes to find a specified
        number of shinies. <a href="#" onclick="showEncounterForm();">Click the blue panel above or this link to run in this mode</a></li>
      </ul>
    </div>
    <div class="w3-panel" id="formpanel">
      <div class="w3-quarter">
        <form class="w3-container" action="#" onsubmit="runSimulation(event);return false;">
          <label for="numsims">Number of People</label>
          <input class="w3-input" type="number" id="numsims" name="numsims" min="1" value="25" />
          <label for="threshold">Minimum shinies</label>
          <input class="w3-input" type="number" id="threshold" name="threshold" min="0" value="0" />
          <label for="numsims">Max # encounters</label>
          <input class="w3-input" type="number" id="numruns" name="numruns" min="0" value="150" /><br />
          <label for="numsims">Shiny probability</label><br />
          1 / <input class="w3-input inline" type="number" id="simprob" name="simprob" min="1" value="50" />
          <input type="submit" value="Run" />
        </form>
      </div>
      <div class="w3-half" id="resultspanel">
        <h2>Real-Time Results</h2>
        <table class="w3-table-all" id="threshtbl">
          <tr>
            <td># of people who found <span id="countspan"></span> shinies:</td>
            <td id="anacount" class="result"></td>
          </tr>
          <tr>
            <td>Avg/Min/Max encounters before finding <span id="threshspan"></span> shinies:</td>
            <td id="anastats" class="result"></td>
          </tr>
        </table>
        <table class="w3-table-all" id="counttbl">
          <tr>
            <td>Average number of shinies found per person</td>
            <td id="avgshinycount" class="result"></td>
          </tr>
          <tr>
            <td>Min/Max number of shinies found by any one person</td>
            <td id="shinystats" class="result"></td>
          </tr>
        </table>

        <h2>Simulations</h2>
        <table class="w3-table-all" id="simtbl">
          <thead>
            <tr><th>Person</th><th>Encounters</th><th>Shinies</th><th>Prob</th><th>Current</th><th>Winning probs</th></tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <h2>DISCUSSION</h2>
        <p>If you are looking for the average number of shinies caught, the analysis
          section shows you that result over the given maximum number of encounters.
          If you are looking for how many encounters it takes to find a specified number of shinies, then each
          individual simulation stops and reports how many encounters it took to find that number of shinies. This section displays statistics related to minimum and
          maximum and average number of encounters to find the specified number of shinies. Note that simulations which do not meet the required
          number of shinies are not included in the results section.</p>
        <h2>Source Code</h2>
        <a href="https://github.com/kartoone/shinyprob">https://github.com/kartoone/shinyprob</a>
      </div>
    </div>

    <!-- Footer -->
    <footer class="w3-container w3-padding-16 w3-light-grey">
      <h4>goxp.org</h4>
      <p>Template design provided for free by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
    </footer>

    <!-- End page content -->
  </div>


  <script>
    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");

    // Get the DIV with overlay effect
    var overlayBg = document.getElementById("myOverlay");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }

    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }
  </script>
</body>
</html>
