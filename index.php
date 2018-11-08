<html>
<head>
  <title>goxp.org - leaderboard tracking for pokemon go</title>
  <script type="text/JavaScript" src="randomsim.js"></script>
  <style>
  label {display:inline-block;width:250px;font-size:1.1em}
  input {font-size:1.1em}
  </style>
</head>
<body>
  <h1>goxp.org - leaderboard tracking for pokemon go</h1>
  <p>
  Shortly after having the idea to automate some of the leaderboard tracking tasks associated with Pokemon Go, I was hit by a car while riding my bike. That life-changing event and a lot of other things have pushed the project into the backburner. In the meantime, though enjoy a simulator below which will simulate people trying to find shiny (or perfect) pokemon.
</p>
  <h1>Shiny Probability Simulator</h1>
  <p>
    This website uses Javascript to runs repeated simulations to show how many 
    shinies a particular person finds given a known (or guessed) probability
    of each encounter being a shiny.
  </p>
  <form action="#" onsubmit="runSimulation(event);return false;">
    <label for="numsims">Number of People</label>
    <input type="number" id="numsims" name="numsims" value="25" /><br />
    <label for="numsims">Max # of Encounters per Person</label>
    <input type="number" id="numruns" name="numruns" value="100" /><br />
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
</body>
</html>
