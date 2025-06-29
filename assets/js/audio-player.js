
document.addEventListener("DOMContentLoaded", function () {
  const audioPlayer = document.getElementById("bg-music");
  const playerContainer = document.getElementById("audioPlayerContainer");
  const playPauseBtn = document.getElementById("playPauseBtn");
  const volumeBtn = document.getElementById("volumeBtn");
  const progressContainer = document.getElementById("progressContainer");
  const progressBar = document.getElementById("progressBar");
  const currentTimeEl = document.getElementById("currentTime");
  const durationEl = document.getElementById("duration");

  let isPlaying = false;
  let isMuted = false;
  let currentVolume = 0.7;

  if (!audioPlayer || !playerContainer) return;

  // Initialize audio player
  audioPlayer.volume = currentVolume;
  audioPlayer.addEventListener('loadedmetadata', updateDuration);
  audioPlayer.addEventListener('timeupdate', updateProgress);
  audioPlayer.addEventListener('ended', handleAudioEnd);

  // Play/Pause functionality
  playPauseBtn.addEventListener('click', togglePlayPause);

  function togglePlayPause() {
    if (isPlaying) {
      audioPlayer.pause();
      playPauseBtn.innerHTML = '<i class="bx bx-play"></i>';
      playerContainer.classList.remove('playing');
      isPlaying = false;
    } else {
      audioPlayer.play().then(() => {
        playPauseBtn.innerHTML = '<i class="bx bx-pause"></i>';
        playerContainer.classList.add('playing');
        isPlaying = true;
      }).catch(e => {
        console.log('Error playing audio:', e);
        showAudioNotification('Unable to play audio', 'error');
      });
    }
  }

  // Volume control
  volumeBtn.addEventListener('click', toggleMute);

  function toggleMute() {
    if (isMuted) {
      audioPlayer.volume = currentVolume;
      volumeBtn.innerHTML = '<i class="bx bx-volume-full"></i>';
      isMuted = false;
    } else {
      currentVolume = audioPlayer.volume;
      audioPlayer.volume = 0;
      volumeBtn.innerHTML = '<i class="bx bx-volume-mute"></i>';
      isMuted = true;
    }
  }

  // Progress bar functionality
  progressContainer.addEventListener('click', setProgress);

  function setProgress(e) {
    const width = this.clientWidth;
    const clickX = e.offsetX;
    const duration = audioPlayer.duration;
    audioPlayer.currentTime = (clickX / width) * duration;
  }

  function updateProgress() {
    const { duration, currentTime } = audioPlayer;
    const progressPercent = (currentTime / duration) * 100;
    progressBar.style.width = `${progressPercent}%`;
    
    currentTimeEl.textContent = formatTime(currentTime);
  }

  function updateDuration() {
    durationEl.textContent = formatTime(audioPlayer.duration);
  }

  function handleAudioEnd() {
    playPauseBtn.innerHTML = '<i class="bx bx-play"></i>';
    playerContainer.classList.remove('playing');
    progressBar.style.width = '0%';
    isPlaying = false;
  }

  function formatTime(time) {
    if (isNaN(time)) return '0:00';
    
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
  }

  // Enhanced volume control with mouse wheel
  playerContainer.addEventListener("wheel", function (e) {
    e.preventDefault();
    const volumeChange = e.deltaY > 0 ? -0.1 : 0.1;
    let newVolume = audioPlayer.volume + volumeChange;
    
    newVolume = Math.max(0, Math.min(1, newVolume));
    audioPlayer.volume = newVolume;
    
    // Update volume icon
    if (newVolume === 0) {
      volumeBtn.innerHTML = '<i class="bx bx-volume-mute"></i>';
      isMuted = true;
    } else if (newVolume < 0.5) {
      volumeBtn.innerHTML = '<i class="bx bx-volume-low"></i>';
      isMuted = false;
    } else {
      volumeBtn.innerHTML = '<i class="bx bx-volume-full"></i>';
      isMuted = false;
    }
    
    showVolumeIndicator(Math.round(newVolume * 100));
  }, { passive: false });

  // Double click to minimize/expand
  let isMinimized = false;
  playerContainer.addEventListener("dblclick", function () {
    if (isMinimized) {
      this.style.transform = 'scale(1)';
      this.style.opacity = '1';
      isMinimized = false;
    } else {
      this.style.transform = 'scale(0.8)';
      this.style.opacity = '0.8';
      isMinimized = true;
    }
  });

  // Keyboard shortcuts
  document.addEventListener('keydown', function(e) {
    if (e.target.tagName.toLowerCase() === 'input' || 
        e.target.tagName.toLowerCase() === 'textarea') return;
    
    switch(e.key.toLowerCase()) {
      case ' ':
        e.preventDefault();
        togglePlayPause();
        break;
      case 'm':
        toggleMute();
        break;
      case 'arrowleft':
        audioPlayer.currentTime = Math.max(0, audioPlayer.currentTime - 5);
        break;
      case 'arrowright':
        audioPlayer.currentTime = Math.min(audioPlayer.duration, audioPlayer.currentTime + 5);
        break;
    }
  });

  // Save and load preferences
  function savePreferences() {
    localStorage.setItem('audioVolume', audioPlayer.volume);
    localStorage.setItem('audioMuted', isMuted);
  }

  function loadPreferences() {
    const savedVolume = localStorage.getItem('audioVolume');
    const savedMuted = localStorage.getItem('audioMuted') === 'true';
    
    if (savedVolume) {
      audioPlayer.volume = parseFloat(savedVolume);
      currentVolume = audioPlayer.volume;
    }
    
    if (savedMuted) {
      toggleMute();
    }
  }

  audioPlayer.addEventListener('volumechange', savePreferences);
  loadPreferences();

  // Volume indicator
  function showVolumeIndicator(volume) {
    let indicator = document.querySelector(".volume-indicator");
    if (!indicator) {
      indicator = document.createElement("div");
      indicator.className = "volume-indicator";
      indicator.style.cssText = `
        position: fixed;
        bottom: 120px;
        right: 50px;
        background: linear-gradient(135deg, #b71c1c, #8b0000);
        color: white;
        padding: 12px 16px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 700;
        z-index: 102;
        opacity: 0;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(183, 28, 28, 0.4);
        border: 2px solid #ffeb3b;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
      `;
      document.body.appendChild(indicator);
    }

    indicator.innerHTML = `<i class="bx bx-volume-full"></i> ${volume}%`;
    indicator.style.opacity = "1";
    indicator.style.transform = "translateY(-10px)";

    clearTimeout(indicator.fadeTimeout);
    indicator.fadeTimeout = setTimeout(() => {
      indicator.style.opacity = "0";
      indicator.style.transform = "translateY(0)";
    }, 1500);
  }

  // Audio notification
  function showAudioNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `audio-notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
      position: fixed;
      top: 100px;
      right: 20px;
      background: ${type === 'error' ? '#f44336' : '#2196f3'};
      color: white;
      padding: 12px 16px;
      border-radius: 8px;
      font-weight: 500;
      z-index: 103;
      opacity: 0;
      transition: opacity 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.style.opacity = '1', 100);
    setTimeout(() => {
      notification.style.opacity = '0';
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  // Initialize with a subtle hint
  setTimeout(() => {
    if (!isPlaying) {
      showAudioNotification('Click play to enjoy background music!', 'info');
    }
  }, 3000);
});
