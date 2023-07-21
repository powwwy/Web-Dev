 const startButton = document.getElementById('startButton');
    const pauseButton = document.getElementById('pauseButton');
    const resetButton = document.getElementById('resetButton');
    const timeDisplay = document.getElementById('time');
    const taskCountDisplay = document.getElementById('taskCount');
    const streakCountDisplay = document.getElementById('streakCount');
    const workDurationInput = document.getElementById('workDuration');
    const breakDurationInput = document.getElementById('breakDuration');
    const updateButton = document.getElementById('updateButton');

    let countdown;
    let seconds;
    let isPaused = false;
    let taskCount = 0;
    let streakCount = 0;
    let isWorkTime = true;
    let longestStreak = 0;

    function updateTimer() {
      seconds = isWorkTime ? workDurationInput.value * 60 : breakDurationInput.value * 60;
      timeDisplay.textContent = `${isWorkTime ? workDurationInput.value : breakDurationInput.value}:00`;
    }

    function switchToBreak() {
      isWorkTime = false;
      updateTimer();
      clearInterval(countdown);
      startButton.disabled = false;
      pauseButton.disabled = true;
      pauseButton.textContent = 'Pause';
      isPaused = false;

      // Update the longest streak if applicable
      if (streakCount > longestStreak) {
        longestStreak = streakCount;
        // You can add code here to save the longestStreak value to your database via an API call.
      }
    }

    function switchToWork() {
      isWorkTime = true;
      updateTimer();
      clearInterval(countdown);
      startButton.disabled = false;
      pauseButton.disabled = true;
      pauseButton.textContent = 'Pause';
      isPaused = false;
    }

    function timer() {
      const minutes = Math.floor(seconds / 60);
      let remainingSeconds = seconds % 60;

      remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

      timeDisplay.textContent = `${minutes}:${remainingSeconds}`;
      seconds--;

      if (seconds < 0) {
        clearInterval(countdown);
        if (isWorkTime) {
          timeDisplay.textContent = 'Time up! \nBreak time start!';
          taskCount++;
          taskCountDisplay.textContent = taskCount;
          streakCount++;
          streakCountDisplay.textContent = streakCount;
          setTimeout(switchToBreak, 2000); // Delay before switching to break
        } else {
          timeDisplay.textContent = 'Break up!';
          setTimeout(switchToBreak, 2000);
          switchToWork();
        }
      }
    }

    updateButton.addEventListener('click', () => {
      updateTimer();
      switchToWork();
    });

    startButton.addEventListener('click', () => {
      countdown = setInterval(timer, 1000);
      startButton.disabled = true;
      pauseButton.disabled = false;
    });

    pauseButton.addEventListener('click', () => {
      if (isPaused) {
        countdown = setInterval(timer, 1000);
        pauseButton.textContent = 'Pause';
        isPaused = false;
      } else {
        clearInterval(countdown);
        pauseButton.textContent = 'Resume';
        isPaused = true;
      }
    });

    resetButton.addEventListener('click', () => {
      clearInterval(countdown);
      updateTimer();
      switchToWork();
      streakCount = 0;
      streakCountDisplay.textContent = streakCount;
    });

