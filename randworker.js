var myid = false;
var thresh = false;
var runs = false;
var prob = false;
var succ = 0;
var total = 0;
self.addEventListener('message',function(e) {
  myid = e.data[0];
  runs = e.data[1];
  prob = 1.0/e.data[2];
  thresh = e.data[3];
  nextsim();
});

function nextsim() {
  var cur = Math.random();
  if (cur<=prob) {
    succ++;
  }
  total++;
  setTimeout(function(){
    self.postMessage([myid,succ,total,cur,prob]);
    if((!thresh && total<runs) || (thresh && succ<thresh && total<runs)) {
      nextsim();
    }
  },Math.round(Math.random()*100));
}
