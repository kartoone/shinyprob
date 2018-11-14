var myid = false;
var thresh = false;
var runs = false;
var prob = false;
var succ = 0;
var total = 0;
var delay = 100;
var realtime = true;
var successes = [];
self.addEventListener('message',function(e) {
  if (e.data[0]<0) { // flag to update delay
    delay = e.data[1]!==false?e.data[1]:delay;
    realtime = e.data[2];
  } else {
    myid = e.data[0];
    runs = e.data[1];
    prob = 1.0/e.data[2];
    thresh = e.data[3];
    delay = e.data[4];
    realtime = e.data[5];
    nextsim(Math.random());
  }
});

function nextsim(cur) {
  total++;
  if (cur<prob) {
    succ++;
    successes.push([myid, succ, total, cur, prob]);
  }
  if (realtime) {
    self.postMessage([myid,succ,total,cur,prob]);
    setTimeout(function(){nextsimhelper(cur);},Math.round(Math.random()*delay));
  } else {
    nextsimhelper(cur);
  }
}

function nextsimhelper(cur) {
  if((!thresh && total<runs) || (thresh && succ<thresh && total<runs)) {
    nextsim(Math.random());
  } else {
    if (!realtime) { // send final stats message(s)
      if (successes.length>0) {
        for (let s of successes) { // send all the success messages at once at the end if realtime updates were disabled
          self.postMessage(s);
        }
        // check the last success to make sure it didn't happen on the last encounter, if not then send one final total message
        let last = successes.pop();
        if (last[2]<runs-1 && (thresh<1 || succ<thresh)) { // only do this check if we are doing average shinies or we failed to find required number of shinies
          self.postMessage([myid,succ,total,cur,prob]); // send one last final update if last success wasn't last run
        }
      } else {
        self.postMessage([myid,succ,total,cur,prob]); // handle poor workers who have no successes
      }
    }
    self.postMessage([-1,myid]); // let main thread know we are done
  }
}
