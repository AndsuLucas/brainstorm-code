export function setupCounter(element) {
  return new Promise((resolve, reject) => {
    let tries = 0;
    const interval = setInterval(() => {
      console.log('interavel');
      if (element) {
        console.log('element', element);
        clearInterval(interval)
        resolve(element);
      }
    });
  })
  
}
