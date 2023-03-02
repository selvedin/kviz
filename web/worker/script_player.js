let BASE_URL = null;
const ACTION_PATH = "quiz/get-started";
self.addEventListener(
  "message",
  function (e) {
    BASE_URL = e.data.BASE_URL;
  },
  false
);

const getResults = () => {
  if (BASE_URL)
    fetch(`${BASE_URL}${ACTION_PATH}`)
      .then(results => results.json())
      .then(data => {
        postMessage(data);
      });
  setTimeout("getResults()", 1000);
};

getResults();
