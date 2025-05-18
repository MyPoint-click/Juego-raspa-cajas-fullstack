/**
 * Creates a throttled function that only invokes the provided function at most once per
 * specified interval
 * 
 * @param {Function} func The function to throttle
 * @param {number} limit The time limit in milliseconds
 * @returns {Function} The throttled function
 */
export const throttle = (func, limit) => {
  let inThrottle;
  
  return function(...args) {
    const context = this;
    
    if (!inThrottle) {
      func.apply(context, args);
      inThrottle = true;
      
      setTimeout(() => {
        inThrottle = false;
      }, limit);
    }
  };
};

/**
 * Creates a debounced function that delays invoking the provided function until after
 * the specified wait time has elapsed since the last time it was invoked
 * 
 * @param {Function} func The function to debounce
 * @param {number} wait The wait time in milliseconds
 * @returns {Function} The debounced function
 */
export const debounce = (func, wait) => {
  let timeout;
  
  return function(...args) {
    const context = this;
    
    clearTimeout(timeout);
    
    timeout = setTimeout(() => {
      func.apply(context, args);
    }, wait);
  };
};

/**
 * Gets a random item from an array
 * 
 * @param {Array} array The array to get a random item from
 * @returns {*} A random item from the array
 */
export const getRandomItem = (array) => {
  return array[Math.floor(Math.random() * array.length)];
};