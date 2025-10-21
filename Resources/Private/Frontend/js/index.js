// Controller Map for Explicit Imports (using relative paths)
const controllerMap = {
    SwiperController: () => import('./controllers/SwiperController.js'),
};

// Cache for loaded controllers to avoid multiple imports
let controllerCache = {};

// Generic Function to Initialize Controllers using the Map
function initializeController(controllerClass) {
    if (controllerCache[controllerClass]) {
        return; // Controller is already loaded
    }

    const loader = controllerMap[controllerClass];

    if (loader) {
        loader()
            .then(({ default: Controller }) => {
                if (Controller) {
                    try {
                        controllerCache[controllerClass] = new Controller();
                    } catch (error) {
                        console.error(`Failed to initialize ${controllerClass} controller:`, error);
                    }
                }
            })
            .catch((error) => {
                console.error(`Failed to load controller for ${controllerClass}:`, error);
            });
    } else {
        console.error(`Controller ${controllerClass} not found.`);
    }
}

// Initialize Core Controllers using the Map
function initializeCoreControllers() {
    initializeController('SwiperController');
}

// Flag to check if features have been initialized
let featuresInitialized = false;

// Initialize all features
function initializeFeatures() {
    if (featuresInitialized) {
        return;
    }
    featuresInitialized = true;

    initializeCoreControllers();
}

// Event Listener for DOMContentLoaded to Start Initializing Features
document.addEventListener('DOMContentLoaded', () => {
    initializeFeatures();
});