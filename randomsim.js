var workers = [];
var workerstats = {};
var workersstopped = 0; // flag to let us know when all workers have finished
const MAXREALTIMESIMS = 450; // this is the limit for realtime display on my system

function runSimulation() {
  event.preventDefault();
  var sims = Number(document.getElementById("numsims").value);     // number of people being simulated
  var thresh = Number(document.getElementById("threshold").value); // required number of shinies for mode 2 (blue mode, i.e, # of encounters mode)
  var runs = Number(document.getElementById("numruns").value);     // maximum number of encounters before terminating individual person sim
  var prob = Number(document.getElementById("simprob").value);     // probability of encountering a shiny
  document.getElementById('threshspan').innerHTML = thresh;
  document.getElementById('countspan').innerHTML = thresh;
  document.getElementById('busyicon').style.display = '';
  document.getElementById('runbtn').disabled = true;
  document.getElementById('stopbtn').disabled = false;
  killWorkers();
  resetRows();
  resetStats();
  setupBuckets(thresh,runs,prob);
  if (sims>MAXREALTIMESIMS) {
    document.getElementById('realtimecheckbox').checked = false;
    document.getElementById('realtimecheckbox').disabled = true;
  }
  workerstats.threshold = thresh;
  let checked = document.getElementById('realtimecheckbox').checked;
  let delay = checked?sims:0; // increase artificial delay as # of sims increases to give main DOM thread a chance to keep up with all the messages
  for (var i = 0; i < sims; i++) {
    workers[i] = new Worker("randworker.js");
    workers[i].id = i;
    workers[i].stats = {}; // setup a new place to keep track of this workers stats
    addRow(i);
    workers[i].addEventListener('message',function(e){
      if (e.data[0]==-1) {
        // worker just finished, check to see if this was the last workers
        if (++workersstopped == workers.length) {
          stopSimulation();
          workersstopped = 0;
        }
      } else {
        // worker sends back [id, successes, total, currentrand, currentprob]
        updateRow(e.data);
        updateStats(e.data);
      }
    });
    workers[i].postMessage([i,runs,prob,thresh,delay,checked]);
  }
}

// if user clicks the enableRealtime button, then force the #sims to max
function enableRealtime() {
  if (document.getElementById('realtimecheckbox').checked) {
    if (Number(document.getElementById('numsims').value)>MAXREALTIMESIMS) {
      document.getElementById('numsims').value=MAXREALTIMESIMS;
    }
  }
}

function stopSimulation() {
  event.preventDefault();
  document.getElementById('runbtn').disabled = false;
  document.getElementById('stopbtn').disabled = true;
  document.getElementById('realtimecheckbox').disabled = false;
  document.getElementById('busyicon').style.display = 'none';
  killWorkers();
}

function resetStats() {
  workersstopped = 0;
  workerstats.maxshinies = false;
  workerstats.minshinies = false;
  workerstats.totshinies = 0;
  workerstats.maxruns = false;
  workerstats.minruns = false;
  workerstats.totruns = 0;
  workerstats.successful = []; // init an empty array of workers who have reached the threshold
  workerstats.buckets = {count:[],thresh:[]};
}

//buckets is an interesting data structure
//multi-level nested object ... two buckets, count and thresh, which each are objects
//with keys that are the starting value for the current range and values which is
//an array of the workers who fall in that range.
//the bucket ranges are determined ahead of time based on the sim parameters,
// for the count bucket, its based on expected value of the number of Shinies
// for the thresh bucket, its based on threshold and expected value
function setupBuckets(thresh,runs,prob) {
  setupCountBucket(runs,prob);
  setupThreshBucket(thresh,runs,prob);
}

function setupCountBucket(runs,prob) {
  // expected value is simply number of runs divided by probability (odds)
  let e = runs/prob;

  // now that we have expected value, let's try to divide into 10 buckets
  let r = Math.ceil(e/3.5); // should be one most of the time

  // walk over the range and initialize all the keys in the count bucket
  min = 0;
  for (let i = 0; i<7; i++) {
    workerstats.buckets.count.push({min:min,max:min+r-1,workers:[]}); // initialize the workers to
    min = min + r;
  }

  // insert a cell in the bucketcounttbl for each of the ranges as headers
  let tbl = document.getElementById('bucketcounttbl');
  tbl.innerHTML = "";
  let headrow = tbl.insertRow(-1);
  let countrow = tbl.insertRow(-1);
  countrow.setAttribute("id","bucketcountrow");
  let islast = 0;
  for (let b of workerstats.buckets.count) {
    var cell = headrow.insertCell(-1);
    cell.setAttribute("style","font-weight:bold");
    if (++islast == workerstats.buckets.count.length) {
      cell.innerHTML = `[${b.min}+]<br />shinies`;
    } else if (b.min==b.max) {
      cell.innerHTML = `[${b.min}]<br />shinies`;
    } else {
      cell.innerHTML = `[${b.min}-${b.max}]<br />shinies`;
    }
    var datacell = countrow.insertCell(-1);
    datacell.innerHTML = 0;
  }
}

function setupThreshBucket(thresh,runs,prob) {

}

function updateBuckets(data) {
  let worker = workers[data[0]];
  updateCountBucket(worker);
  updateThreshBucket(worker);
}

function updateCountBucket(worker) {
  // find the right bucket to put the woker into
  let hit = false;
  for (let b of workerstats.buckets.count) {
    b.workers = b.workers.filter(w => worker.id!=w.id);
    if (worker.stats.succ>=b.min && worker.stats.succ<=b.max) {
      hit = true; // we found the bucket for this worker
      b.workers.push(worker);
      break;
    }
  }

  // if the worker didn't fit into any of the predefined buckets, put them into the last bucket
  if (!hit) {
    workerstats.buckets.count[workerstats.buckets.count.length-1].workers.push(worker);
  }

  // finally update the counts in all the buckets
  var countrow = document.getElementById("bucketcountrow");
  var cells = countrow.children;
  var childi = 0;
  for (let b of workerstats.buckets.count) {
    cells[childi++].innerHTML = b.workers.length;
  }

}

function updateThreshBucket(worker) {

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
  if (data[3]<data[4]) {
    // we have a winner ... let's freeze this winner by inserting a cell
    var row = document.getElementById('row'+i);
    var cell = row.insertCell(-1);
    cell.innerHTML = data[3] + ' ('+data[2]+')';
    cell.classList.add('winningprob');
  }
  var curcell = document.getElementById('cur'+i);
  curcell.innerHTML = data[3];
  var probcell = document.getElementById('prob'+i);
  probcell.innerHTML = data[4];
}

function updateStats(data) {
  updateAllStats(data);
  updateSuccessful(data);
  updateBuckets(data);
}

function updateAllStats(data) {
  let worker = workers[data[0]];
  worker.stats.total = data[2];
  worker.stats.succ = data[1];
  // no easy way to optimixe this one unless you kept track of the worker(s)
  // who had the min number of shinies and then when they got a new shiny
  // you updated the minimum number of shinies.
  // Far easier to simply reanalyze all workers
  workerstats.totshinies = 0;
  workerstats.minshinies = false;
  workerstats.maxshinies = false;
  for (let w of workers) {
    if (w.stats.total>0) {
      if (workerstats.maxshinies===false || w.stats.succ>workerstats.maxshinies) {
        workerstats.maxshinies = w.stats.succ;
      }
      if (workerstats.minshinies===false || w.stats.succ<workerstats.minshinies) {
        workerstats.minshinies = w.stats.succ;
      }
      workerstats.totshinies += w.stats.succ;
    }
  }

  let avg = Math.round(workerstats.totshinies*10/workers.length)/10.0;
  document.getElementById('avgshinycount').innerHTML = avg;
  if (workerstats.minshinies===false || workerstats.maxshinies==false) {
    document.getElementById('shinystats').innerHTML = 0 + " / " + 0;
  } else {
    document.getElementById('shinystats').innerHTML = workerstats.minshinies + " / " + workerstats.maxshinies;
  }
}

// keep track of how many workers have reached threshold
function updateSuccessful(data) {
  let hit = false;
  for (let w of workerstats.successful) {
    if (w.id == data[0]) {
      hit = true;
      break;
    }
  }
  if (!hit && data[1] >= workerstats.threshold) {
    let w = workers[data[0]];
    workerstats.totruns += w.stats.total; // only count the number of encounters for those who have reached the threshold
    workerstats.successful.push(w);
    if (workerstats.maxruns===false || w.stats.total>workerstats.maxruns) {
      workerstats.maxruns = w.stats.total;
    }
    if (workerstats.minruns===false || w.stats.total<workerstats.minruns) {
      workerstats.minruns = w.stats.total;
    }
  }
  document.getElementById('anacount').innerHTML = workerstats.successful.length;
  let avgruns = workerstats.successful.length>0?Math.round(workerstats.totruns/workerstats.successful.length):0;
  if (workerstats.minruns===false||workerstats.maxruns===false) {
    document.getElementById('anastats').innerHTML = 0 + " / " + 0 + " / " + 0;
  } else {
    document.getElementById('anastats').innerHTML = avgruns + " / " + workerstats.minruns + " / " + workerstats.maxruns;
  }
  if (workerstats.threshold>0) {
    highlightEncounters();
  } else {
    highlightShinies();
  }
}

function highlightShinies() {
  if (workerstats.maxshinies===false || workerstats.minshinies===false) {
    return; // if we don't have any winners/losers yet, don't highlight anything
  }
  for (let w of workers) {
    var workerrow = document.getElementById('row'+w.id);
    workerrow.classList.remove('w3-red'); // remove any current highlights
    workerrow.classList.remove('w3-green');
    if (w.stats.succ === workerstats.minshinies) {
      workerrow.classList.add('w3-red'); // remove any current highlights
    } else if (w.stats.succ === workerstats.maxshinies) {
      workerrow.classList.add('w3-green'); // remove any current highlights
    }
  }
}

function highlightEncounters() {
  if (workerstats.maxruns===false || workerstats.minruns===false) {
    return; // if we don't have any winners/losers yet, don't highlight anything
  }
  for (let w of workerstats.successful) {
    var workerrow = document.getElementById('row'+w.id);
    workerrow.classList.remove('w3-red'); // remove any current highlights
    workerrow.classList.remove('w3-green');
    if (w.stats.total === workerstats.maxruns) {
      workerrow.classList.add('w3-red'); // remove any current highlights
    } else if (w.stats.total === workerstats.minruns) {
      workerrow.classList.add('w3-green'); // remove any current highlights
    }
  }
}

function hideForms() {
  if (event)
    event.preventDefault();
  document.getElementById('formpanel').style.display = 'none';
  document.getElementById('avgpanel').style.opacity = 0.6;
  document.getElementById('encpanel').style.opacity = 0.6;
  document.getElementById('menuoverview').classList.remove('w3-blue');
  document.getElementById('menuoverview').classList.add('w3-blue');
  document.getElementById('menuavg').classList.remove('w3-blue');
  document.getElementById('menuenc').classList.remove('w3-blue');
}

function showAverageForm() {
  event.preventDefault();
  document.getElementById('bucketcounttbl').style.display = '';
  document.getElementById('bucketthreshtbl').style.display = 'none';
  document.getElementById('counttbl').style.display = '';
  document.getElementById('threshtbl').style.display = 'none';
  document.getElementById('formpanel').style.display = '';
  document.getElementById('threshold').value = '0';
  document.getElementById('threshold').style.display = 'none';
  document.getElementById('thresholdlabel').style.display = 'none';
  document.getElementById('avgpanel').style.opacity = 1;
  document.getElementById('encpanel').style.opacity = 0.6;
  document.getElementById('menuavg').classList.remove('w3-blue');
  document.getElementById('menuavg').classList.add('w3-blue');
  document.getElementById('menuoverview').classList.remove('w3-blue');
  document.getElementById('menuenc').classList.remove('w3-blue');
}

function showEncounterForm() {
  event.preventDefault();
  document.getElementById('bucketcounttbl').style.display = 'none';
  document.getElementById('bucketthreshtbl').style.display = '';
  document.getElementById('counttbl').style.display = 'none';
  document.getElementById('threshtbl').style.display = '';
  document.getElementById('formpanel').style.display = '';
  document.getElementById('threshold').style.display = '';
  document.getElementById('thresholdlabel').style.display = '';
  document.getElementById('threshold').value = '1';
  document.getElementById('avgpanel').style.opacity = 0.6;
  document.getElementById('encpanel').style.opacity = 1;
  document.getElementById('menuenc').classList.remove('w3-blue');
  document.getElementById('menuenc').classList.add('w3-blue');
  document.getElementById('menuoverview').classList.remove('w3-blue');
  document.getElementById('menuavg').classList.remove('w3-blue');
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
