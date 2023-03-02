if (typeof worker == "undefined") {
  worker = new Worker("./../worker/script_player.js");
}
worker.postMessage({ BASE_URL: BASE_URL });

worker.onmessage = function (event) {
  if (!mainApp.isRemote) {
    worker.terminate();
    worker = undefined;
  }
  mainApp.started = event.data;
};
