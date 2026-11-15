<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroAnalogs | Planetary Analog Intelligence Platform</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-space: #02050e;
            --cyan-glow: #22d3ee;
            --blue-accent: #3b82f6;
        }

        body {
            margin: 0;
            overflow: hidden;
            background-color: var(--bg-space);
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
        }

        .font-mono { font-family: 'Space Mono', monospace; }

        .starfield {
            background-image: 
                radial-gradient(1px 1px at 15% 25%, rgba(255,255,255,0.6), transparent),
                radial-gradient(1px 1px at 35% 70%, rgba(255,255,255,0.4), transparent),
                radial-gradient(1.5px 1.5px at 75% 20%, rgba(34,211,238,0.7), transparent),
                radial-gradient(1px 1px at 85% 65%, rgba(255,255,255,0.5), transparent),
                radial-gradient(1px 1px at 50% 85%, rgba(255,255,255,0.4), transparent);
            background-size: 550px 550px;
        }

        .earth-glow {
            box-shadow: 0 0 70px 14px rgba(59, 130, 246, 0.35), inset 0 0 45px rgba(34, 211, 238, 0.4);
        }

        .orbit-ring-inner {
            width: 560px;
            height: 560px;
            border: 1px solid rgba(34, 211, 238, 0.14);
            border-radius: 50%;
        }
        .orbit-ring-outer {
            width: 960px;
            height: 960px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        @keyframes scrollDot {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(8px); opacity: 0.3; }
        }
        .animate-scroll-dot { animation: scrollDot 1.8s infinite ease-in-out; }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden select-none">

    <section id="landing-view" class="h-screen w-screen relative starfield flex items-center justify-center transition-opacity duration-500">
        
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="orbit-ring-outer absolute"></div>
            <div class="orbit-ring-inner absolute"></div>
        </div>

        <div class="relative flex items-center justify-center">
            <div id="landing-earth" class="w-[360px] h-[360px] sm:w-[440px] sm:h-[440px] rounded-full overflow-hidden earth-glow relative bg-[#07152e]"></div>
            
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 z-10 pointer-events-none">
                <span class="text-xs sm:text-sm font-semibold tracking-[0.45em] text-cyan-300 uppercase mb-3">
                    ASTROANALOGS
                </span>
                <h1 class="text-2xl sm:text-4xl md:text-[42px] font-light tracking-[0.22em] text-white uppercase whitespace-nowrap drop-shadow-lg">
                    EARTH TO MARS ANALOGS
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 font-light tracking-wide mt-3 max-w-xl drop-shadow">
                    Identify Earth locations that analog the permanent Moon base locations and Mars.
                </p>
                
                <button onclick="enterMissionControl()" class="pointer-events-auto mt-7 px-9 py-3.5 rounded-full border border-cyan-400/60 bg-black/40 backdrop-blur-md text-xs sm:text-sm font-semibold tracking-[0.22em] text-white uppercase hover:bg-cyan-500/20 hover:border-cyan-300 hover:shadow-[0_0_25px_rgba(34,211,238,0.45)] transition-all duration-300 cursor-pointer">
                    GUEST LOGIN / EXPLORE
                </button>
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
            <button onclick="enterMissionControl()" class="flex items-center gap-3 group cursor-pointer focus:outline-none">
                <span class="w-10 h-10 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm group-hover:border-cyan-400 group-hover:bg-cyan-500/10 transition-all">
                    <i class="fa-solid fa-bars text-sm text-white"></i>
                </span>
                <span class="text-sm sm:text-base font-medium tracking-wide text-white group-hover:text-cyan-300 transition-colors">
                    Menu
                </span>
            </button>
        </div>

        <div class="fixed top-7 right-8 z-20 flex items-center gap-3">
            <button onclick="enterMissionControl()" aria-label="Search" class="w-10 h-10 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer">
                <i class="fa-solid fa-magnifying-glass text-sm text-white"></i>
            </button>
            <button onclick="enterMissionControl()" aria-label="Account" class="w-10 h-10 rounded-full border border-white/35 flex items-center justify-center bg-black/30 backdrop-blur-sm hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer">
                <i class="fa-regular fa-user text-sm text-white"></i>
            </button>
            <button onclick="enterMissionControl()" class="px-5 py-2.5 rounded-full border border-white/35 bg-black/30 backdrop-blur-sm text-sm font-medium tracking-wide text-white hover:border-cyan-400 hover:bg-cyan-500/10 transition-all cursor-pointer">
                Mission Portal
            </button>
        </div>

        <div class="fixed bottom-7 left-8 z-20">
            <label for="newsletter-email" class="block text-sm font-semibold tracking-wide text-white mb-2.5">
                Get Updates
            </label>
            <form onsubmit="event.preventDefault(); enterMissionControl();" class="flex items-center w-64 sm:w-72 rounded-full border border-white/35 bg-black/40 backdrop-blur-sm pl-4 pr-1.5 py-1.5 focus-within:border-cyan-400 transition-colors">
                <input id="newsletter-email" type="email" placeholder="Enter Email Address" class="bg-transparent text-xs sm:text-sm text-white placeholder-gray-400 focus:outline-none w-full pr-2" />
                <button type="submit" aria-label="Subscribe" class="w-8 h-8 rounded-full border border-white/40 flex items-center justify-center shrink-0 hover:bg-cyan-500/20 hover:border-cyan-400 transition-all cursor-pointer">
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
            <span class="text-sm font-semibold tracking-wide text-white mb-2.5">Follow Us</span>
            <div class="flex items-center gap-3">
                <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-full border border-white/35 flex items-center justify-center bg-black/30 text-sm text-white hover:border-cyan-400 hover:text-cyan-300 transition-all"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="Twitter" class="w-9 h-9 rounded-full border border-white/35 flex items-center justify-center bg-black/30 text-sm text-white hover:border-cyan-400 hover:text-cyan-300 transition-all"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-full border border-white/35 flex items-center justify-center bg-black/30 text-sm text-white hover:border-cyan-400 hover:text-cyan-300 transition-all"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </section>

    <script type="module">
        import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js';

        function initLandingEarth() {
            const container = document.getElementById('landing-earth');
            if (!container) return;

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

            const earth = new THREE.Mesh(
                geometry,
                new THREE.MeshStandardMaterial({ map: earthTexture, roughness: 0.6, metalness: 0.1 })
            );
            earth.rotation.y = 4.2;
            earth.rotation.x = 0.25;
            scene.add(earth);

            scene.add(new THREE.AmbientLight(0xffffff, 0.5));
            const sun = new THREE.DirectionalLight(0xffffff, 2.4);
            sun.position.set(3, 2, 4);
            scene.add(sun);

            window.addEventListener('resize', () => {
                if (!container) return;
                renderer.setSize(container.clientWidth, container.clientHeight);
            });

            function animate() {
                requestAnimationFrame(animate);
                earth.rotation.y += 0.0012;
                renderer.render(scene, camera);
            }
            animate();
        }
        initLandingEarth();

        window.enterMissionControl = function() {
            window.location.href = '/dashboard';
        };
    </script>
</body>
</html>