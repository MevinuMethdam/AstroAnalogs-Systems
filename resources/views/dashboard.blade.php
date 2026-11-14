<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroAnalogs | Earth to Mars Analogs</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #02040a;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
        }

        .starfield {
            background-image: 
                radial-gradient(1px 1px at 15% 25%, rgba(255,255,255,0.6), transparent),
                radial-gradient(1px 1px at 35% 70%, rgba(255,255,255,0.4), transparent),
                radial-gradient(1.5px 1.5px at 75% 20%, rgba(255,255,255,0.7), transparent),
                radial-gradient(1px 1px at 85% 65%, rgba(255,255,255,0.5), transparent),
                radial-gradient(1px 1px at 50% 85%, rgba(255,255,255,0.4), transparent),
                radial-gradient(1px 1px at 20% 80%, rgba(255,255,255,0.5), transparent);
            background-size: 550px 550px;
        }

        .earth-glow {
            box-shadow: 0 0 65px 12px rgba(59, 130, 246, 0.35), inset 0 0 40px rgba(96, 165, 250, 0.4);
        }

        .orbit-ring-inner {
            width: 560px;
            height: 560px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }
        .orbit-ring-outer {
            width: 960px;
            height: 960px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        @keyframes scrollDot {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(8px); opacity: 0.3; }
        }
        .animate-scroll-dot {
            animation: scrollDot 1.8s infinite ease-in-out;
        }
    </style>
</head>
<body class="h-screen w-screen relative starfield select-none flex items-center justify-center">

    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div class="orbit-ring-outer absolute"></div>
        <div class="orbit-ring-inner absolute"></div>
    </div>

    <div class="relative flex items-center justify-center">
        <div id="earth-globe" class="w-[360px] h-[360px] sm:w-[440px] sm:h-[440px] rounded-full overflow-hidden earth-glow relative bg-[#07152e]"></div>
        
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 z-10 pointer-events-none">
            <span class="text-xs sm:text-sm font-medium tracking-[0.45em] text-blue-300/90 uppercase mb-3">
                ASTROANALOGS
            </span>
            <h1 class="text-2xl sm:text-4xl md:text-[40px] font-light tracking-[0.22em] text-white uppercase whitespace-nowrap drop-shadow-md">
                EARTH TO MARS ANALOGS
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 font-light tracking-wide mt-3 max-w-xl drop-shadow">
                Identify Earth locations that analog the permanent Moon base locations and Mars.
            </p>
            
            <a href="/dashboard" class="pointer-events-auto mt-7 px-9 py-3.5 rounded-full border border-white/45 bg-black/25 backdrop-blur-sm text-xs sm:text-sm font-medium tracking-[0.22em] text-white uppercase hover:bg-white/15 hover:border-blue-400 transition-all duration-300">
                GUEST LOGIN / EXPLORE
            </a>
        </div>
    </div>

    <div class="fixed top-7 left-1/2 -translate-x-1/2 z-20">
        <svg class="w-10 h-10 text-white/90" viewBox="0 0 64 40" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="22" cy="18" r="11" />
            <path d="M28 24 L35 31" stroke-linecap="round" />
            <circle cx="42" cy="18" r="11" />
            <path d="M48 24 L55 31" stroke-linecap="round" />
        </svg>
    </div>

    <div class="fixed top-7 left-8 z-20">
        <button class="flex items-center gap-3 group cursor-pointer focus:outline-none">
            <span class="w-10 h-10 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm group-hover:border-blue-400 group-hover:bg-blue-500/10 transition-all">
                <i class="fa-solid fa-bars text-sm text-white"></i>
            </span>
            <span class="text-sm sm:text-base font-medium tracking-wide text-white group-hover:text-blue-300 transition-colors">
                Menu
            </span>
        </button>
    </div>

    <div class="fixed top-7 right-8 z-20 flex items-center gap-3">
        <button aria-label="Search" class="w-10 h-10 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm hover:border-blue-400 hover:bg-blue-500/10 transition-all cursor-pointer">
            <i class="fa-solid fa-magnifying-glass text-sm text-white"></i>
        </button>
        <button aria-label="Account" class="w-10 h-10 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm hover:border-blue-400 hover:bg-blue-500/10 transition-all cursor-pointer">
            <i class="fa-regular fa-user text-sm text-white"></i>
        </button>
        <a href="#" class="px-5 py-2.5 rounded-full border border-white/35 bg-black/30 backdrop-blur-sm text-sm font-medium tracking-wide text-white hover:border-blue-400 hover:bg-blue-500/10 transition-all">
            Shop with us
        </a>
    </div>

    <div class="fixed bottom-7 left-8 z-20">
        <label for="newsletter-email" class="block text-sm font-semibold tracking-wide text-white mb-2.5">
            Get Updates
        </label>
        <form onsubmit="event.preventDefault();" class="flex items-center w-64 sm:w-72 rounded-full border border-white/35 bg-black/40 backdrop-blur-sm pl-4 pr-1.5 py-1.5 focus-within:border-blue-400 transition-colors">
            <input 
                id="newsletter-email" 
                type="email" 
                placeholder="Enter Email Address" 
                class="bg-transparent text-xs sm:text-sm text-white placeholder-gray-400 focus:outline-none w-full pr-2"
            />
            <button type="submit" aria-label="Subscribe" class="w-8 h-8 rounded-full border border-white/40 flex items-center justify-center shrink-0 hover:bg-blue-500/20 hover:border-blue-400 transition-all cursor-pointer">
                <i class="fa-solid fa-paper-plane text-xs text-white"></i>
            </button>
        </form>
    </div>

    <div class="fixed bottom-7 left-1/2 -translate-x-1/2 z-20 pointer-events-none hidden sm:flex flex-col items-center">
        <div class="w-6 h-10 rounded-full border border-white/40 flex justify-center pt-2">
            <div class="w-1 h-2 bg-white rounded-full animate-scroll-dot"></div>
        </div>
    </div>

    <div class="fixed bottom-7 right-8 z-20 flex flex-col items-end">
        <span class="text-sm font-semibold tracking-wide text-white mb-2.5">
            Follow QQ
        </span>
        <div class="flex items-center gap-3">
            <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm text-sm text-white hover:border-blue-400 hover:text-blue-300 transition-all">
                <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="#" aria-label="Twitter" class="w-9 h-9 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm text-sm text-white hover:border-blue-400 hover:text-blue-300 transition-all">
                <i class="fa-brands fa-twitter"></i>
            </a>
            <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm text-sm text-white hover:border-blue-400 hover:text-blue-300 transition-all">
                <i class="fa-brands fa-instagram"></i>
            </a>
        </div>
    </div>

    <script type="module">
        import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js';

        const container = document.getElementById('earth-globe');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 1000);
        camera.position.z = 2.85;

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);

        const geometry = new THREE.SphereGeometry(1, 64, 64);
        const textureLoader = new THREE.TextureLoader();
        const earthTexture = textureLoader.load('https://threejs.org/examples/textures/planets/earth_atmos_2048.jpg');
        earthTexture.colorSpace = THREE.SRGBColorSpace;

        const material = new THREE.MeshStandardMaterial({
            map: earthTexture,
            roughness: 0.6,
            metalness: 0.1
        });

        const earth = new THREE.Mesh(geometry, material);
        earth.rotation.y = 4.2;
        earth.rotation.x = 0.25;
        scene.add(earth);

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.45);
        scene.add(ambientLight);

        const sunLight = new THREE.DirectionalLight(0xffffff, 2.4);
        sunLight.position.set(3, 2, 4);
        scene.add(sunLight);

        const rimLight = new THREE.PointLight(0x3b82f6, 3, 10);
        rimLight.position.set(-3, -1, 2);
        scene.add(rimLight);

        function animate() {
            requestAnimationFrame(animate);
            earth.rotation.y += 0.0012;
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            if (container) {
                renderer.setSize(container.clientWidth, container.clientHeight);
            }
        });
    </script>
</body>
</html>