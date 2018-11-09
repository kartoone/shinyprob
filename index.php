<html>
<head>
  <title>goxp.org - leaderboard tracking for pokemon go</title>
  <script type="text/JavaScript" src="randomsim.js"></script>
  <style>
  label {display:inline-block;width:150px;font-size:1.1em;padding:6px}
  input {font-size:1.1em}
  span {font-weight:bold;font-size:1.2em}
  </style>
</head>
<body>
  <h1>goxp.org - leaderboard tracking for pokemon go</h1>
  <p>
  Lots of ideas for leaderboard tracking and automation, but so little time!!!
  </p>
  <h1>Shiny Probability Simulator</h1>
  <p>
    This website uses Javascript to run repeated simulations to show how many
    shinies a particular person finds given a known (or guessed) probability
    of each encounter being a shiny.
  </p>
  <form action="#" onsubmit="runSimulation(event);return false;">
    <label for="numsims">Number of People</label>
    <input type="number" id="numsims" name="numsims" value="25" /><br />
    <label for="threshold">Minimum shinies</label>
    <input type="number" id="threshold" name="threshold" value="0" /> (Leave blank or 0 to see how many shinies people find over given number of encounters below)<br />
    <label for="numsims">Max # encounters</label>
    <input type="number" id="numruns" name="numruns" value="150" /><br />
    <label for="numsims">Shiny probability</label>
    1 / <input type="number" id="simprob" name="simprob" value="50" />
    <input type="submit" value="Run" />
  </form>
  <h2>Simulations</h2>
  <table id="simtbl">
    <thead>
      <tr><th>Person</th><th>Encounters</th><th>Shinies</th><th>Prob</th><th>Current</th><th>Winning probs</th></tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  <h2>Simulation Analysis</h2>
  <p>This section shows analysis related to the specified number of shinies you entered. If you left the "Minimum shinies" field blank or 0, then this section
    summarizes how many shinies people found over the given number of max encounters. If you specified a non-zero number for "Minimum shinies", then each
    individual simulation stops and reports how many encounters it took to find that number of shinies. This section displays statistics related to minimum and
    maximum and average number of encounters to find the specified number of shinies.</p>
  <table id="threshtbl">
    <tr>
      <td># of people who found <span id="countspan"></span> shinies:</td>
      <td id="anacount"></td>
    </tr>
    <tr>
      <td>Avg/Min/Max encounters before finding <span id="threshspan"></span> shinies:</td>
      <td id="anastats"></td>
    </tr>
  </table>
  <table id="counttbl">
    <tr>
      <td>Average number of shinies found per person in <span id="simsspan"></span> encounters:</td>
      <td id="avgshinycount"></td>
    </tr>
    <tr>
      <td>Min/Max number of shinies found by single person in <span id="statsspan"></span> encounters:</td>
      <td id="shinystats"></td>
    </tr>
  </table>
  <h2>Source Code</h2>
  <a href="https://github.com/kartoone/shinyprob">https://github.com/kartoone/shinyprob</a>
</body>
</html>
