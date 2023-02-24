let BASE_URL = null;
let LAST_CHECK = null;
const ACTION_PATH = "site/check-status";
self.addEventListener(
  "message",
  function (e) {
    BASE_URL = e.data.BASE_URL;
    LAST_CHECK = e.data.LAST_CHECK;
  },
  false
);

const getResults = () => {
  if (BASE_URL && LAST_CHECK)
    fetch(`${BASE_URL}${ACTION_PATH}?last=${LAST_CHECK}`)
      .then(results => results.json())
      .then(data => {
        postMessage(data);
      });
  setTimeout("getResults()", 5000);
};

getResults();
