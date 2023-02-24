if (typeof worker == "undefined") {
  worker = new Worker("worker/script.js");
}
worker.postMessage({ BASE_URL: BASE_URL, LAST_CHECK: mainApp.lastCheck });

worker.onmessage = function (event) {
  // console.log(event);
  mainApp.hasNewQuiz = event.data.hasNewQuiz;
  mainApp.lastCheck = event.data.lastCheck;
  // if (event.data.length) {
  //   worker.terminate();
  //   worker = undefined;
  // }
};
