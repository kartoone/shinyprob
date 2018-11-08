var myid = false;
var runs = false;
var prob = false;
var succ = 0;
var total = 0;
self.addEventListener('message',function(e) {
  myid = e.data[0];
  runs = e.data[1];
  prob = 1.0/e.data[2];
  nextsim();
});

function nextsim() {
  var cur = Math.random();
  if (cur<=prob) {
    succ++;
  }
  total++;
  setTimeout(function(){self.postMessage([myid,succ,total,cur,prob]);if(total<runs){nextsim()}},100);
}
