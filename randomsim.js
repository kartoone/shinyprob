var workers = [];
var workerstats = {};

function runSimulation(event) {
  event.preventDefault();
  var sims = Number(document.getElementById("numsims").value);
  var thresh = Number(document.getElementById("threshold").value);
  var runs = Number(document.getElementById("numruns").value);
  var prob = Number(document.getElementById("simprob").value);
  if (thresh) {
    document.getElementById('counttbl').style.display = 'none';
    document.getElementById('threshtbl').style.display = '';
  } else {
    document.getElementById('counttbl').style.display = '';
    document.getElementById('threshtbl').style.display = 'none';
  }
  killWorkers();
  resetRows();
  workerstats.threshold = thresh;
  workerstats.successful = []; // init an empty array of workers who have reached the threshold
  for (var i = 0; i < sims; i++) {
    workers[i] = new Worker("randworker.js");
    workers[i].stats = {}; // setup a new place to keep track of this workers stats
    addRow(i);
    workers[i].addEventListener('message',function(e){
      // worker sends back [id, successes, total, currentrand, currentprob]
      updateRow(e.data);
      updateStats(e.data);
    });
    workers[i].postMessage([i,runs,prob,thresh]);
  }
}

function addRow(i) {
  var tbl = document.getElementById('simtbl');
  var row = tbl.insertRow(-1);
  row.setAttribute('id','row'+i);
  var cell = row.insertCell(-1);
  cell.innerHTML = i+1;
  cell = row.insertCell(-1);
  cell.setAttribute('id','total'+i);
  cell = row.insertCell(-1);
  cell.setAttribute('id','succ'+i);
  cell = row.insertCell(-1);
  cell.setAttribute('id','prob'+i);
  cell = row.insertCell(-1);
  cell.setAttribute('id','cur'+i);
}

function updateRow(data) {
  var i = data[0]; // worker who sent their data to us
  var totalcell = document.getElementById('total'+i);
  totalcell.innerHTML = data[2];
  var succcell = document.getElementById('succ'+i);
  succcell.innerHTML = data[1];
  if (Number(data[3])<=Number(data[4])) {
    // we have a winner ... let's freeze this winner by inserting a cell
    var row = document.getElementById('row'+i);
    var cell = row.insertCell(-1);
    cell.innerHTML = data[3];
  }
  var curcell = document.getElementById('cur'+i);
  curcell.innerHTML = data[3];
  var probcell = document.getElementById('prob'+i);
  probcell.innerHTML = data[4];
}

function updateStats(data) {
  updateAllStats(data);
  updateSuccessfulStats(data);
}

function updateAllStats(data) {
  workers[data[0]].stats.total = data[2];
  workers[data[0]].stats.succ = data[1];
  let maxshinies = false;
  let minshinies = false;
  let totshinies = 0;
  for (let w of workers) {
    if (maxshinies===false || w.stats.succ>maxshinies) {
      maxshinies = w.stats.succ;
    }
    if (minshinies===false || w.stats.succ<minshinies) {
      minshinies = w.stats.succ;
    }
    totshinies += w.stats.succ;
  }
  let avg = Math.round(totshinies*10/workers.length)/10.0;
  document.getElementById('avgshinycount').innerHTML = avg;
  document.getElementById('shinystats').innerHTML = minshinies + " / " + maxshinies;
}

// keep track of how many workers have reached threshold
function updateSuccessfulStats(data) {
  let hit = false;
  for (let w of workerstats.successful) {
    if (w.id == data[0]) {
      w.total = data[2];
      w.succ = data[1];
      hit = true;
      break;
    }
  }
  if (!hit && data[1] >= workerstats.threshold) {
    workerstats.successful.push({id:data[0],total:data[2],succ:data[1]});
  }
  document.getElementById('anacount').innerHTML = workerstats.successful.length;
  let totalruns = 0;
  let maxruns = false;
  let minruns = false;
  for (let w of workerstats.successful) {
    if (maxruns===false || w.total>maxruns) {
      maxruns = w.total;
    }
    if (minruns===false || w.total<minruns) {
      minruns = w.total;
    }
    totalruns += w.total;
  }
  let avgruns = workerstats.successful.length>0?Math.round(totalruns/workerstats.successful.length):0;
  document.getElementById('anastats').innerHTML = avgruns + " / " + minruns + " / " + maxruns;
}

function resetRows() {
  var tbl = document.getElementById('simtbl');
  while (tbl.rows.length>1) {
    tbl.deleteRow(1);
  }
}

function killWorkers() {
  for (var i=0;i<workers.length;i++) {
    workers[i].terminate();
  }
  workers = [];
  workerstats = {};
}
