// Theme Toggle Functionality
const themeToggleButton = document.getElementById('themeToggleButton');

themeToggleButton.addEventListener('click', () => {
  const currentTheme = getCurrentTheme();
  let nextTheme;
  if (currentTheme === 'light') {
    nextTheme = 'dark';
  } else if (currentTheme === 'dark') {
    nextTheme = 'purple';
  } else if (currentTheme === 'purple') {
    nextTheme = 'light';
  } else {
    nextTheme = 'light';
  }

  setTheme(nextTheme);
  updateToggleButtonLabel(nextTheme);
});

function getCurrentTheme() {
  const themeStyle = document.getElementById('theme-style').getAttribute('href');
  return themeStyle.replace('andromax-', '').replace('.css', '');
}

function setTheme(theme) {
  const themeStyle = document.getElementById('theme-style');
  themeStyle.href = `andromax-${theme}.css`;
  localStorage.setItem('selectedTheme', theme);
}

function updateToggleButtonLabel(theme) {
  const toggleButtonLabel = document.getElementById('toggleButtonLabel');
  if (theme === 'light') {
    toggleButtonLabel.textContent = 'Toggle Dark Theme';
  } else if (theme === 'dark') {
    toggleButtonLabel.textContent = 'Toggle Purple Theme';
  } else if (theme === 'purple') {
    toggleButtonLabel.textContent = 'Toggle Light Theme';
  }
}

// Check for the selected theme in local storage and set it
document.addEventListener('DOMContentLoaded', () => {
  const selectedTheme = localStorage.getItem('selectedTheme');
  if (selectedTheme) {
    setTheme(selectedTheme);
    updateToggleButtonLabel(selectedTheme);
  }
});

