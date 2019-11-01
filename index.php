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
    .panellink { text-decoration: none }
    .panellink:hover {font-weight:bold; font-size:1.1em}
    #resultspanel {margin-left:20px}
    #simtbl td.winningprob { min-width:250px }
  </style>
  <script src="randomsim.js"></script>
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
      <a href="#" onclick="hideForms();" class="w3-bar-item w3-button w3-padding w3-blue" id="menuoverview"><i class="fas fa-info-circle"></i>  Overview</a>
      <a href="#" onclick="showAverageForm();" class="w3-bar-item w3-button w3-padding" id="menuavg"><i class="fas fa-grin-stars "></i>  Average # Shinies</a>
      <a href="#" onclick="showEncounterForm();" class="w3-bar-item w3-button w3-padding" id="menuenc"><i class="fa fa-eye"></i>  Average # Encounters</a>
    </div>
  </nav>


  <!-- Overlay effect when opening sidebar on small screens -->
  <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

  <!-- !PAGE CONTENT! -->
  <div class="w3-main" style="margin-left:300px;margin-top:43px;overflow:scroll">

    <!-- Header -->
    <header class="w3-container" style="padding-top:22px">
      <h1>Shiny Probability Simulator</h1>
    </header>

    <div class="w3-row-padding w3-margin-bottom">
      <div class="w3-half">
        <a class="panellink" href="#" onclick="showAverageForm();">
          <div id="avgpanel" class="w3-container w3-red w3-padding-16" style="opacity:0.75">
            <div class="w3-left"><i class="fas fa-grin-stars w3-xxxlarge"></i></div>
              <h3>&nbsp; Average # of Shinies</h3>
              Simulate how many shinies people find over a specified
              number of encounters.
          </div>
        </a>
      </div>
      <div class="w3-half">
        <a class="panellink" href="#" onclick="showEncounterForm();">
          <div id="encpanel" class="w3-container w3-indigo w3-padding-16" style="opacity:0.75">
            <div class="w3-left"><i class="fa fa-eye w3-xxxlarge"></i></div>
            <h3>&nbsp; Average # of Encounters</h3>
            Simulate how many encounters it takes to find a specified
            number of shinies.
          </div>
        </a>
      </div>

    <div class="w3-panel">
      <h3>Overview</h3>
	  <b>Update: normal shiny probability is now set at 1:512 based on idea that Niantic might be using some binary-related server optimization. Most people agree 1:450 was never correct from the initial Silph Road research study. So the two likely candiates are 1:500 or 1:512. The probability of perfects can be known exactly assuming Niantic implementation is fair and accurate.</b>
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
        <form class="w3-container w3-card-4" action="#" onsubmit="runSimulation();return false;">
          <h3>Setup your simulation parameters</h3>
          <label for="numsims">Number of People</label>
          <input class="w3-input" type="number" id="numsims" name="numsims" min="1" max="2500" value="25" />
          <label id="thresholdlabel" for="threshold">Number of shinies</label>
          <input class="w3-input" type="number" id="threshold" name="threshold" min="0" value="0" />
          <label for="numsims">Max # encounters</label>
          <input class="w3-input" type="number" id="numruns" name="numruns" min="0" value="500" />
          <label for="numsims">Shiny probability</label><br />
          1 / <input class="w3-input inline" type="number" id="simprob" name="simprob" min="1" value="512" step="0.1" onchange="updateSelect(this.value)" />
          <select name="guessedprobs" id="guessedprobs" onchange="document.getElementById('simprob').value=this.value;">
            <option value="100" id="customoption">Custom</custom>
            <option value="22.5">Shiny: community day boosted probability</option>
            <option value="50">Shiny: special event (e.g., cubone, ponyta event)</option>
            <option value="512" selected="selected">Shiny: normal non-boosted probability</option>
            <option value="216">Perfect: egg hatch or raid</option>
            <option value="1728">Perfect: weather boosted spawn</option>
            <option value="64">Perfect: lucky trade</option>
            <option value="1331">Perfect: best friend trade</option>
            <option value="2197">Perfect: ultra friend trade</option>
            <option value="2744">Perfect: great friend trade</option>
            <option value="3375">Perfect: good friend trade</option>
          </select>
          <br />
          <div style="width:100%;text-align:left;margin:10px 0 20px 0">
          <input id="runbtn" class="w3-button w3-black w3-hover-green" type="submit" value="Run Simulation">
          <input id="stopbtn" class="w3-button w3-black w3-hover-red" type="button" value="Stop Simulation" disabled="disabled" onclick="stopSimulation();return false;">
          <img src="ajax-loader.gif" style="display:none" id="busyicon" />
          </div>
        </form>
      </div>
      <div class="w3-half" id="resultspanel" style="background:white;opacity:1">
        <h2 style="margin-bottom:0">Cumulative Results</h2>
        <table class="w3-table-all" id="threshtbl">
          <tr>
            <td id="anacount" class="result"></td>
            <td>Number of people who found <span id="countspan"></span> shiny(s)</td>
          </tr>
          <tr>
            <td id="anastats" class="result"></td>
            <td>Avg/Min/Max encounters before finding <span id="threshspan"></span> shiny(s)</td>
          </tr>
        </table>
        <table class="w3-table-all" id="counttbl">
          <tr>
            <td id="avgshinycount" class="result"></td>
            <td>Average number of shinies found per person</td>
          </tr>
          <tr>
            <td id="shinystats" class="result"></td>
            <td>Min/Max number of shinies found by any one person</td>
          </tr>
        </table>

        <h2 style="margin-bottom:0">Bucket Analysis</h2>
        <p style="margin:0;padding:0">(Grouping results into "buckets", e.g., how many people fell within each indicated range)</p>
        <table class="w3-table-all" id="bucketthreshtbl">
          <tr><td>Re-run simulation to see correct buckets for the specified number of shinies</td></tr>
        </table>
        <table class="w3-table-all" id="bucketcounttbl">
        </table>

        <h2 style="margin-bottom:0">Simulations</h2>
        <form style="margin:0;padding:0;">
          <input type="checkbox" id="realtimecheckbox" checked="checked" value="1" onchange="enableRealtime();" /> Display real-time updates (when enabled only 450 sims can be run simultaneously)
        </form>
        <table class="w3-table-all" id="simtbl">
          <thead>
            <tr><th>Person</th><th>Encounters</th><th>Shinies</th><th>Prob</th><th>Current</th><th>Winning probs (encounter #)</th></tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <h2>DISCUSSION</h2>
        <p>If you are looking for the average number of shinies caught, the Cumulative
          results section at the top of this page shows the average, minimum, and maximum number
          of shinies caught over the given maximum number of encounters.
        </p>
        <p>
          If you are looking for how many encounters it takes to find a specified number of shinies, then each
          individual simulation stops and reports how many encounters it took to find that number of shinies. The
          Cumulative results section displays statistics related to the minimum,
          maximum, and average number of encounters required to find the specified number of shinies. Note that simulations which do not meet the required
          number of shinies are not included in the results section. Note: if you want to make sure all simulations
          are counted in this mode, then enter a high max encounter limit.</p>
        <p><b>Finding perfects</b> - change the "odds" from 1 in 50 to 1 in 4096 to get the odds of finding a non-weather boosted perfect.</p>
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
    hideForms();
  </script>
</body>
</html>
