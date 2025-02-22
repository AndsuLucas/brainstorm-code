import javascriptLogo from './javascript.svg'
import { setupCounter } from './counter.js'

document.querySelector('#app').innerHTML = `
  <div>
    <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript" target="_blank">
      <img src="${javascriptLogo}" class="logo vanilla" alt="JavaScript logo" />
    </a>
    <h1>Hello Vite!</h1>
    <div class="card">
      <button id="addCounterButton" type="button">Add counter button</button>
    </div>
  </div>
`

let counter = 0

const setCounter = (count, element) => {
  counter = count
  element.innerHTML = `count is ${counter}`
}

const setupEvent = (element) => element.addEventListener('click', () => setCounter(counter + 1, element))

setupCounter(document.querySelector('#counter1')).then((element) => {
  setupEvent(element);
});


document.querySelector('#addCounterButton').addEventListener('click', () => {
  const newElement = document.createElement('button')
  newElement.id = `counter1`
  newElement.innerHTML = `count is ${counter}`;

  document.querySelector('.card').appendChild(newElement)
  setupCounter(newElement).then((element) => {
    setupEvent(element);
  });
});
