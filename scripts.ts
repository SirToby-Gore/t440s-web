/**
 * Interface defining the expected structure of a song object.
 */
interface Song {
    'file-path': string;
    'song-id': string;
    'title': string;
    'artist-name': string;
    'album-id': string;
    'album-title': string;
    'artist-id': string;
    'cover-image-path': string;
    [key: string]: any;
}

/**
 * Handles switching between different tab sections when a button is clicked.
 */
(window as any).openTab = function (evt: MouseEvent, tabName: string): void {
    let i: number;
    
    const tabContent: HTMLCollectionOf<Element> = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].classList.remove("active");
    }

    const tabButtons: HTMLCollectionOf<Element> = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove("active");
    }

    const targetContent = document.getElementById(tabName);
    if (targetContent) {
        targetContent.classList.add("active");
    }
    
    const currentTarget = evt.currentTarget as HTMLElement;
    if (currentTarget) {
        currentTarget.classList.add("active");
    }
};

/**
 * Initializes the tabs on page load.
 */
(window as any).initializeTabs = function (): void {
    const tabContainers = document.querySelectorAll('.tabs-container');
    
    tabContainers.forEach(container => {
        let initialActiveButton = container.querySelector('.tab-button.active') as HTMLElement | null;
        
        if (!initialActiveButton) {
            initialActiveButton = container.querySelector('.tab-button') as HTMLElement | null;
        }

        if (initialActiveButton) {
            const onclickValue = initialActiveButton.getAttribute('onclick');
            const tabNameMatch = onclickValue ? onclickValue.match(/openTab\((?:event|[^,]+),\s*'([^']*)'\)/) : null;
            
            if (tabNameMatch && tabNameMatch[1]) {
                const tabName = tabNameMatch[1];
                
                initialActiveButton.closest('.tabs-container')?.parentElement
                    ?.querySelectorAll('.tab-content')
                    .forEach(content => content.classList.remove('active'));
                
                const targetContent = document.getElementById(tabName);
                if (targetContent) {
                    targetContent.classList.add('active');
                    initialActiveButton.classList.add('active');
                }
            }
        }
    });
};


document.addEventListener('DOMContentLoaded', () => {

    // --- Core Elements and State Variables ---
    const audio = document.getElementById('play-bar-audio') as HTMLAudioElement;
    const source = document.getElementById('play-bar-source') as HTMLSourceElement;

    const artImg = document.getElementById('play-bar-art') as HTMLImageElement;
    const titleSpan = document.getElementById('play-bar-title') as HTMLSpanElement;
    const artistLink = document.getElementById('play-bar-artist-link') as HTMLAnchorElement;
    const albumLinkSpan = document.getElementById('play-bar-album-span') as HTMLSpanElement;
    const previousButton = document.getElementById('previous-button') as HTMLButtonElement;
    const restartButton = document.getElementById('restart-button') as HTMLButtonElement;
    const nextButton = document.getElementById('next-button') as HTMLButtonElement;
    const playPauseIconContainer = document.getElementById('play-pause-icon') as HTMLSpanElement;
    const progressBar = document.getElementById('progress-bar') as HTMLDivElement;
    const progressBarFill = document.getElementById('progress-bar-fill') as HTMLDivElement;
    const volumeSlider = document.getElementById('volume-slider') as HTMLInputElement;
    
    // Constant for skipping time
    const SKIP_TIME_SECONDS = 10;

    if (!audio || !source) {
        console.error("Audio player elements not found. Cannot initialize player.");
        return;
    }

    let playQueue: Song[] = JSON.parse(localStorage.getItem('play-queue') ?? '[]');
    let currentQueueIndex: number = JSON.parse(localStorage.getItem('queue-index') ?? '-1');
    let currentPlayingSong: Song | null = JSON.parse(localStorage.getItem('current-index') ?? 'null');
    
    // NEW: Load saved time position from localStorage
    const savedProgress: { songId: string; time: number } | null = JSON.parse(localStorage.getItem('playback-progress') ?? 'null');


    // --- Utility Functions ---

    /**
     * Updates the play/pause button icon.
     * @param state - 'playing' or 'paused'.
     */
    function updatePlayPauseButton(state: 'playing' | 'paused'): void {
        if (!playPauseIconContainer) return;
        
        if (state === 'playing') {
            // Pause Icon (two vertical bars)
            playPauseIconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path fill="white" d="M10 8h2v8H10zM14 8h2v8h-2z"/></svg>`;
        } else {
            // Play Icon (triangle)
            playPauseIconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path fill="white" d="M10 8l6 4-6 4z"/></svg>`;
        }
    }

    /**
     * Saves the current state of the play queue, index, and song to localStorage.
     */
    function savePlayerState(): void {
        try {
            localStorage.setItem('play-queue', JSON.stringify(playQueue));
            localStorage.setItem('queue-index', JSON.stringify(currentQueueIndex));
            localStorage.setItem('current-index', JSON.stringify(currentPlayingSong));
        } catch (e) {
            console.error('Failed to save player state:', e);
        }
    }

    /**
     * Reads and parses the 'play-queue-data' element set by PHP.
     * NOTE: This function's name is kept as 'getQueueFromCookie' for consistency with the user's provided,
     * but it correctly reads the PHP-generated JSON from the hidden <pre> tag.
     * @returns {Song[]} The array of song objects from the data element, or an empty array.
     */
    function getQueueFromCookie(): Song[] {
        const dataElement = document.getElementById('play-queue-data') as HTMLPreElement;
        if (!dataElement || !dataElement.innerHTML) {
            return [];
        }
        try {
            return JSON.parse(dataElement.innerHTML) as Song[];
        } catch (e) {
            console.error('Failed to parse play queue data from PHP element:', e);
            return [];
        }
    }

    /**
     * Shuffles an array using the Fisher-Yates algorithm.
     * @template T
     * @param {T[]} array The array to shuffle.
     * @returns {T[]} A new shuffled array.
     */
    function shuffleArray<T>(array: T[]): T[] {
        const newArray: T[] = [...array];
        for (let i = newArray.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
        }
        return newArray;
    }

    /**
     * Updates the play bar UI with the current song's details.
     * @param {Song} songData
     */
    function updatePlayBarUI(songData: Song): void {
        if (artImg) artImg.src = songData['cover-image-path'] || 'placeholder.png';
        if (titleSpan) titleSpan.textContent = songData.title || 'Unknown Song';

        if (artistLink) {
            artistLink.textContent = songData['artist-name'] || 'Unknown Artist';
            artistLink.href = songData['artist-id'] ? `?artist=${encodeURIComponent(songData['artist-id'])}` : '#';
        }

        if (albumLinkSpan) {
            albumLinkSpan.textContent = songData['album-title'] ? ` - ${songData['album-title']}` : '';
        }
    }

    // --- Player Core Logic ---

    /**
     * Loads and plays a single song and updates the UI.
     * @param {Song} songData The data object for the song to play.
     */
    function play(songData: Song): void {
        if (!songData || !songData['file-path']) {
            console.error('Invalid song data: Missing file-path', songData);
            return;
        }

        currentPlayingSong = songData;
        updatePlayBarUI(songData);

        // Ensure new track starts from 0 unless resuming from page load
        audio.currentTime = 0; 

        // Set the source directly on the audio element 
        audio.src = songData['file-path'];
        
        // IMPORTANT: Tell the audio element to load the new file
        audio.load();
        
        audio.play().then(() => {
            updatePlayPauseButton('playing');
        }).catch(e => {
            console.warn('Playback failed (browser blocking).', e);
            updatePlayPauseButton('paused');
        });

        savePlayerState();
    }
    /**
     * Plays the next song in the queue, looping back to the start when finished.
     */
    function playNext(): void {
        if (playQueue.length === 0) return;

        currentQueueIndex++;

        if (currentQueueIndex < playQueue.length) {
            play(playQueue[currentQueueIndex]);
        } else {
            console.log('Queue finished. Looping to start.');
            currentQueueIndex = 0;
            play(playQueue[currentQueueIndex]);
        }
    }

    /**
     * Plays the previous song in the queue. Restarts the song if at the start of the current track.
     */
    function playPrevious(): void {
        if (playQueue.length === 0) return;

        // If current song has played for more than 3 seconds, restart it.
        if (audio.currentTime > 3) {
            audio.currentTime = 0;
            audio.play().catch(e => console.error('Audio play failed:', e));
            return;
        }

        currentQueueIndex--;

        if (currentQueueIndex >= 0) {
            play(playQueue[currentQueueIndex]);
        } else {
            // Loop back to the end
            currentQueueIndex = playQueue.length - 1;
            play(playQueue[currentQueueIndex]);
        }
    }

    /**
     * Toggles play/pause state.
     */
    function togglePlayPause(): void {
        if (currentPlayingSong === null && playQueue.length > 0) {
            // If nothing is currently playing, start the queue
            currentQueueIndex = currentQueueIndex === -1 ? 0 : currentQueueIndex;
            play(playQueue[currentQueueIndex]);
            return;
        }

        if (audio.paused) {
            audio.play().then(() => updatePlayPauseButton('playing')).catch(e => console.error('Play failed:', e));
        } else {
            audio.pause();
            updatePlayPauseButton('paused');
        }
    }
    
    /**
     * Adjusts the volume up or down by a step (0.1).
     * @param direction 'up' or 'down'
     */
    function adjustVolume(direction: 'up' | 'down'): void {
        const step = 0.1;
        let newVolume = audio.volume;

        if (direction === 'up') {
            newVolume = Math.min(1.0, audio.volume + step);
        } else {
            newVolume = Math.max(0.0, audio.volume - step);
        }

        audio.volume = newVolume;
        if (volumeSlider) {
            volumeSlider.value = newVolume.toString();
        }
    }
    
    /**
     * Skips playback forward or backward by a set amount of time.
     * @param direction 'forward' (positive) or 'backward' (negative)
     */
    function skipTime(direction: 'forward' | 'backward'): void {
        if (!audio.src) return;

        const skipAmount = direction === 'forward' ? SKIP_TIME_SECONDS : -SKIP_TIME_SECONDS;
        const newTime = audio.currentTime + skipAmount;

        // Ensure the new time is within bounds [0, duration]
        audio.currentTime = Math.max(0, Math.min(audio.duration || Infinity, newTime));
    }


    // --- Initialization and Event Binding ---

    // Initial load from localStorage
    if (currentPlayingSong) {
        updatePlayBarUI(currentPlayingSong);
        audio.src = currentPlayingSong['file-path'] || '';
        audio.load();
        
        // NEW: RESUME LOGIC - Check if the saved song matches the currently loaded song
        if (savedProgress && savedProgress.songId === currentPlayingSong['song-id']) {
            
            // Wait for the audio to be loaded enough to read its duration/metadata
            audio.addEventListener('loadedmetadata', function resumePlayback() {
                 console.log('Resuming playback at:', savedProgress!.time);
                 audio.currentTime = savedProgress!.time;
                 // Remove the listener so it only fires once per page load
                 audio.removeEventListener('loadedmetadata', resumePlayback);
            });
            
            // Set the UI state to what it was when the page was closed
            if (localStorage.getItem('is-playing') === 'true') {
                 // Set the icon to playing, but rely on user input to auto-play due to browser restrictions
                 updatePlayPauseButton('playing'); 
            } else {
                 updatePlayPauseButton('paused');
            }
        } else {
             // If not resuming, set UI based on simple pause state
             if (localStorage.getItem('is-playing') === 'true' && !audio.paused) {
                 updatePlayPauseButton('playing');
             } else {
                 updatePlayPauseButton('paused');
             }
        }
    } else {
        updatePlayBarUI({ 'title': 'Melody Vault', 'artist-name': 'Welcome', 'cover-image-path': 'placeholder.png'} as Song);
    }
    
    // Check if the current page has rendered a new queue and use it as the default queue if no song is playing
    const pageQueue = getQueueFromCookie();
    if (currentPlayingSong === null && pageQueue.length > 0) {
         playQueue = pageQueue;
         savePlayerState();
    }


    // Audio Playback Events
    audio.addEventListener('play', () => {
        updatePlayPauseButton('playing');
        localStorage.setItem('is-playing', 'true');
    });
    audio.addEventListener('pause', () => {
        updatePlayPauseButton('paused');
        localStorage.setItem('is-playing', 'false');
    });
    
    // UPDATED: Clear progress when the song ends naturally
    audio.addEventListener('ended', () => {
        localStorage.removeItem('playback-progress'); 
        playNext();
    });
    
    // Progress Bar Logic
    audio.addEventListener('timeupdate', () => {
        if (audio.duration) {
            const progress = (audio.currentTime / audio.duration) * 100;
            progressBarFill.style.width = `${progress}%`;
        }
    });

    progressBar?.addEventListener('click', (e) => {
        if (!audio.duration) return;
        
        const rect = progressBar.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const width = rect.width;
        const seekTime = (clickX / width) * audio.duration;
        audio.currentTime = seekTime;
    });

    // Control Button Events
    previousButton?.addEventListener('click', playPrevious);
    restartButton?.addEventListener('click', togglePlayPause);
    nextButton?.addEventListener('click', playNext);
    
    // Volume controls
    if (volumeSlider) {
        audio.volume = parseFloat(volumeSlider.value);
        volumeSlider.addEventListener('input', () => {
            audio.volume = parseFloat(volumeSlider.value);
        });
    }
    
    // Navigation Links in Play Bar
    document.querySelector('.play-bar-details')?.addEventListener('click', (e) => {
        const target = e.target as HTMLElement;

        if (currentPlayingSong) {
            // Handle Album link click
            if ((target === albumLinkSpan || target.closest('#play-bar-album-span')) && currentPlayingSong['album-id']) {
                window.location.href = `?album=${encodeURIComponent(currentPlayingSong['album-id'])}`;
            } 
            // Handle Artist link click
            else if (target === artistLink || target.closest('#play-bar-artist-link')) {
                 if (currentPlayingSong['artist-id']) {
                     window.location.href = `?artist=${encodeURIComponent(currentPlayingSong['artist-id'])}`;
                 }
            }
        }
    });

    // Page Action Buttons (Play All / Shuffle All)
    document.getElementById('play-all-button')?.addEventListener('click', () => {
        // Correctly read the page-specific queue from the hidden element
        playQueue = getQueueFromCookie();
        currentQueueIndex = 0;
        if (playQueue.length > 0) {
            play(playQueue[currentQueueIndex]);
        }
    });

    document.querySelectorAll('#shuffle-all-button').forEach(button => {
         button.addEventListener('click', () => {
            // Correctly read the page-specific queue from the hidden element
            playQueue = shuffleArray<Song>(getQueueFromCookie());
            currentQueueIndex = 0;
            if (playQueue.length > 0) {
                play(playQueue[currentQueueIndex]);
            }
        });
    });

    // Individual Song Item Clicks
    document.querySelectorAll('.song-item').forEach(item => {
        item.addEventListener('click', () => {
            const clickedSongId = (item as HTMLElement).dataset.songId;
            if (!clickedSongId) return;

            // Get the queue rendered by PHP on the current page
            const currentPageQueue = getQueueFromCookie();
            
            const startIndex = currentPageQueue.findIndex(song => song['song-id'] === clickedSongId);

            if (startIndex !== -1) {
                playQueue = currentPageQueue; // Update global queue to match the current page's queue
                currentQueueIndex = startIndex;
                play(playQueue[currentQueueIndex]);
            } else {
                console.error('Clicked song not found in queue:', clickedSongId);
            }
        });
    });
    
    // Initialize tabs on page load
    (window as any).initializeTabs();
    
    // Save state on unload
    window.addEventListener('beforeunload', () => {
        // Only save progress if a song is loaded and has played for more than 1 second
        if (currentPlayingSong && audio.src && audio.currentTime > 1) { 
            const progress = {
                songId: currentPlayingSong['song-id'],
                time: audio.currentTime
            };
            localStorage.setItem('playback-progress', JSON.stringify(progress));
        } else {
             // If the song is less than 1 second in (or not loaded), clear any old progress
             localStorage.removeItem('playback-progress');
        }
        
        // Save the standard player state (queue, index, song) too
        savePlayerState(); 
    });
    
    // --- NEW: GLOBAL KEYBOARD SHORTCUTS ---
    document.addEventListener('keydown', (e) => {
        // Prevent shortcuts from firing when user is typing in an input field
        const targetTagName = (e.target as HTMLElement).tagName.toLowerCase();
        if (targetTagName === 'input' || targetTagName === 'textarea') {
            return;
        }

        switch (e.key) {
            case ' ': // Space: Pause/Un-pause
                e.preventDefault(); 
                togglePlayPause();
                break;
            case 'ArrowLeft': // Left Arrow: Skip backward 10s
                e.preventDefault();
                skipTime('backward');
                break;
            case 'ArrowRight': // Right Arrow: Skip forward 10s
                e.preventDefault();
                skipTime('forward');
                break;
            case 'ArrowUp': // Up Arrow: Increase volume
                e.preventDefault();
                adjustVolume('up');
                break;
            case 'ArrowDown': // Down Arrow: Decrease volume
                e.preventDefault();
                adjustVolume('down');
                break;
            case 'p': // P key: Previous track
                e.preventDefault();
                playPrevious();
                break;
            case 'n': // N key: Next track
                e.preventDefault();
                playNext();
                break;
        }
    });
});