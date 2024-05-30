function toggleSearch() {
    var image = document.getElementById('searchImage');
    var input = document.getElementById('searchInputHeader');
    
    var line = document.querySelector(".line");
    if (input.style.display === '') {
        input.style.display = 'inline-block';
        image.style.display = 'none';
        
        line.style.width = '100%';
    } else {
        input.style.display = 'none';
        image.style.display = 'block';
    }
}

/* let switchMode = 1; // 1 for light mode, 0 for dark mode
function SwitchModeVar(){
  if(switchMode===1){
    switchMode=0;
  }else{
    switchMode=1;
  }
}
function toggleDarkMode() {
  const root = document.documentElement;
  const body = document.body;
  

  const colors = {
      '--primaryNotChangin': '#007bff',
      '--pramaruChanginToWhite': '#007bff',
      '--text-ColorToWhite': 'black',
      '--thirdly-color': 'white',
      '--text-color-orange': 'orange',
      '--text-color-red': 'rgb(211, 70, 35)',
      '--background-color-1': '#b9dcf2',
      '--background-color-2': '#fff2e1',
      '--background-color-3': '#fde9e8',
      '--background-color-4': '#e5f5eb',
      '--background-color-5': '#e2f2ff',
      '--background-color-6': '#39bcb1',
      '--text-color': 'red',
      '--secondary-color': '#f8f9fa',
      '--border-BlackToGray': 'black',
      '--color-WhiteToBlack': 'white',
      '--color-WhiteSmokeToBlack': 'WhiteSmoke',
  };

  const darkModeColors = {
      '--primaryNotChangin': '#007bff',
      '--pramaruChanginToWhite': 'white',
      '--text-ColorToWhite': 'white',
      '--thirdly-color': 'white',
      '--text-color-orange': 'orange',
      '--text-color-red': 'rgb(211, 70, 35)',
      '--background-color-1': '#2f3335',
      '--background-color-2': '#452700',
      '--background-color-3': '#3d0805',
      '--background-color-4': '#143425',
      '--background-color-5': '#202324',
      '--background-color-6': '#39bcb1',
      '--text-color': 'red',
      '--secondary-color': '#1b1e1f',
      '--border-BlackToGray': '#736b5e',
      '--color-WhiteToBlack': 'rgb(30, 32, 33)',
      '--color-WhiteSmokeToBlack': 'rgb(30, 32, 33)',
  };

  for (let color in colors) {
      if (switchMode) {
          root.style.setProperty(color, darkModeColors[color]);
          
      } else {
          root.style.setProperty(color, colors[color]);
      }
  }

  SwitchModeVar();

  // Toggle body background color
  const bodyBackgroundColor = getComputedStyle(body).backgroundColor;
  if (bodyBackgroundColor === 'rgb(24, 26, 27)') {
      body.style.backgroundColor = 'white';
      console.log("im in");
  } else {
      body.style.backgroundColor = 'rgb(24, 26, 27)';
  }
} 
 */
  
const colors = {
    '--primaryNotChangin': '#007bff',
    '--pramaruChanginToWhite': '#007bff',
    '--text-ColorToWhite': 'black',
    '--thirdly-color': 'white',
    '--text-color-orange': 'orange',
    '--text-color-red': 'rgb(211, 70, 35)',
    '--background-color-1': '#b9dcf2',
    '--background-color-2': '#fff2e1',
    '--background-color-3': '#fde9e8',
    '--background-color-4': '#e5f5eb',
    '--background-color-5': '#e2f2ff',
    '--background-color-6': '#39bcb1',
    "--background-color-7": "#39bc6b",
    "--background-color-8": "#435cd9",
    "--background-color-9": "#7dd943",
    "--background-color-10": "#bed73f",
    "--background-color-11": "#d0a223",
    "--background-color-12": "#2ca90d",
    '--text-color': 'red',
    '--secondary-color': '#f8f9fa',
    '--border-BlackToGray': 'black',
    '--color-WhiteToBlack': 'white',
    '--color-WhiteSmokeToBlack': 'WhiteSmoke',
  };

  const darkModeColors = {
    '--primaryNotChangin': '#007bff',
    '--pramaruChanginToWhite': 'white',
    '--text-ColorToWhite': 'white',
    '--thirdly-color': 'white',
    '--text-color-orange': 'orange',
    '--text-color-red': 'rgb(211, 70, 35)',
    '--background-color-1': '#2f3335',
    '--background-color-2': '#452700',
    '--background-color-3': '#3d0805',
    '--background-color-4': '#143425',
    '--background-color-5': '#202324',
    '--background-color-6': '#39bcb1',
    "--background-color-7": "#39b06b",
    "--background-color-8": "#4350d9",
    "--background-color-9": "#7dd043",
    "--background-color-10": "#be073f",
    "--background-color-11": "#d00223",
    "--background-color-12": "#2c090d",
    '--text-color': 'red',
    '--secondary-color': '#1b1e1f',
    '--border-BlackToGray': '#736b5e',
    '--color-WhiteToBlack': 'rgb(30, 32, 33)',
    '--color-WhiteSmokeToBlack': 'rgb(30, 32, 33)',
  };
  


// Retrieve switchMode value from localStorage or default to light mode (1)
let switchMode = localStorage.getItem('switchMode') === 'dark' ? 0 : 1;
// Call additional function if switchMode is 0 (dark mode)
if(switchMode===0){
    const root = document.documentElement;
    const body = document.body;
    const checkbox = document.getElementById('checkbox');
    checkbox.checked = true;
    const ball = document.querySelector('.ball');
    ball.style.transition = 'none';
    for (let color in colors) {
    root.style.setProperty(color, darkModeColors[color]);
    }
    const bodyBackgroundColor = getComputedStyle(body).backgroundColor;
    body.style.backgroundColor = 'rgb(24, 26, 27)';
}
// Function to toggle dark mode
function toggleDarkMode() {
  const root = document.documentElement;
  const body = document.body;

  

  // Apply colors based on switchMode
  for (let color in colors) {
    if (switchMode) {
      root.style.setProperty(color, darkModeColors[color]);
    } else {
      root.style.setProperty(color, colors[color]);
    }
  }
  switchMode = switchMode === 1 ? 0 : 1;
  localStorage.setItem('switchMode', switchMode === 1 ? 'light' : 'dark');
  // Toggle body background color
  const bodyBackgroundColor = getComputedStyle(body).backgroundColor;
  if (bodyBackgroundColor === 'rgb(24, 26, 27)') {
      body.style.backgroundColor = 'white';
  } else {
      body.style.backgroundColor = 'rgb(24, 26, 27)';
  }
}