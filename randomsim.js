var workers = [];

function runSimulation(event) {
  event.preventDefault();
  var sims = Number(document.getElementById("numsims").value);
  var runs = Number(document.getElementById("numruns").value);
  var prob = Number(document.getElementById("simprob").value);
  killWorkers();
  resetRows();
  for (var i = 0; i < sims; i++) {
    workers[i] = new Worker("randworker.js");
    addRow(i);
    workers[i].addEventListener('message',function(e){
      // worker sends back [id, successes, total, currentrand, currentprob]
      updateRow(e.data[0],e.data);
    });
    workers[i].postMessage([i,runs,prob]);
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

function updateRow(i,data) {
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
}
