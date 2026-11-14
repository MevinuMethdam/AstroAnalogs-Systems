<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AstroAnalogs | A Taste of Space</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        body {
            background: #02040a;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            position: relative;
        }


        .space-background {
            position: fixed;
            inset: 0;
            z-index: 0;

            background:
                radial-gradient(
                    ellipse at 50% 48%,
                    rgba(20, 52, 91, 0.20) 0%,
                    transparent 48%
                ),
                radial-gradient(
                    ellipse at 85% 65%,
                    rgba(35, 45, 95, 0.12),
                    transparent 40%
                ),
                #02040a;
        }


        .earth-glow {
            position: absolute;

            width: 550px;
            height: 550px;

            top: 50%;
            left: 50%;

            transform: translate(-50%, -50%);

            background: radial-gradient(
                circle,
                rgba(91, 164, 228, 0.30) 0%,
                rgba(43, 111, 180, 0.17) 35%,
                rgba(20, 63, 110, 0.08) 55%,
                transparent 72%
            );

            filter: blur(15px);

            pointer-events: none;
            z-index: 1;
        }


        .orbit-container {
            position: fixed;
            inset: 0;

            display: flex;
            justify-content: center;
            align-items: center;

            overflow: hidden;
            pointer-events: none;

            z-index: 2;
        }

        .orbit {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .orbit-outer {
            width: 130vh;
            height: 130vh;

            border: 1px solid rgba(255,255,255,0.16);
        }

        .orbit-middle {
            width: 88vh;
            height: 88vh;

            border: 1px dashed rgba(255,255,255,0.12);
        }

        .orbit-inner {
            width: 58vh;
            height: 58vh;

            border: 1px dotted rgba(255,255,255,0.10);
        }


        #canvas-container {
            position: fixed;
            inset: 0;

            width: 100%;
            height: 100%;

            z-index: 3;
            pointer-events: none;
        }

        #canvas-container canvas {
            display: block;
            width: 100%;
            height: 100%;
        }


        .main-ui {
            position: relative;

            width: 100%;
            height: 100vh;

            z-index: 10;

            pointer-events: none;
        }


        .header {
            position: absolute;

            top: 10.5%;
            left: 5.7%;
            right: 13%;

            display: flex;
            justify-content: space-between;
            align-items: center;

            pointer-events: auto;
        }

        .menu-button {
            display: flex;
            align-items: center;
            gap: 9px;

            background: transparent;
            border: none;

            color: #fff;

            font-size: 9px;
            font-weight: 500;

            cursor: pointer;
        }

        .menu-icon {
            width: 19px;
            height: 19px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 1px solid rgba(255,255,255,0.45);
            border-radius: 50%;

            font-size: 9px;
        }


        .logo {
            position: absolute;

            left: 50%;
            transform: translateX(-50%);

            font-size: 22px;
            font-weight: 300;

            letter-spacing: -4px;

            color: rgba(255,255,255,0.90);
        }


        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 19px;
            height: 19px;

            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            font-size: 9px;

            cursor: pointer;
            transition: 0.3s;
        }

        .header-icon:hover {
            background: white;
            color: black;
        }

        .shop-button {
            padding: 4px 12px;

            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 20px;

            background: transparent;

            color: white;

            font-size: 8px;
            font-weight: 500;

            cursor: pointer;
            transition: 0.3s;
        }

        .shop-button:hover {
            background: white;
            color: black;
        }


        .center-content {
            position: absolute;

            top: 50%;
            left: 50%;

            transform: translate(-50%, -50%);

            width: 100%;

            display: flex;
            flex-direction: column;
            align-items: center;

            text-align: center;

            pointer-events: auto;

            z-index: 11;
        }

        .center-content h1 {
            font-size: clamp(15px, 2.2vw, 19px);

            font-weight: 400;

            letter-spacing: 2px;

            color: #fff;

            margin-bottom: 3px;

            text-shadow: 0 2px 15px rgba(0,0,0,0.8);
        }

        .center-content p {
            font-size: 9px;

            font-weight: 300;

            letter-spacing: 0.2px;

            color: rgba(255,255,255,0.85);

            margin-bottom: 13px;

            text-shadow: 0 2px 10px rgba(0,0,0,0.9);
        }

        .explore-button {
            width: 117px;
            height: 28px;

            border: 1px solid rgba(255,255,255,0.65);
            border-radius: 30px;

            background: rgba(0,0,0,0.10);

            color: white;

            font-size: 9px;
            font-weight: 500;

            letter-spacing: 1.5px;

            cursor: pointer;

            transition: all 0.4s ease;
        }

        .explore-button:hover {
            background: white;
            color: #05070c;
        }


        .footer {
            position: absolute;

            left: 5.7%;
            right: 13%;

            bottom: 14%;

            display: flex;
            justify-content: space-between;
            align-items: flex-end;

            pointer-events: auto;
        }


        .updates {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .updates-title,
        .social-title {
            font-size: 8px;

            font-weight: 600;

            letter-spacing: 0.2px;
        }

        .email-container {
            position: relative;
        }

        .email-input {
            width: 132px;
            height: 19px;

            padding: 0 26px 0 9px;

            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 20px;

            background: transparent;

            outline: none;

            color: white;

            font-size: 7px;
            font-family: 'Montserrat', sans-serif;
        }

        .email-input::placeholder {
            color: rgba(255,255,255,0.8);
        }

        .email-input:focus {
            border-color: white;
        }

        .email-button {
            position: absolute;

            right: 2px;
            top: 50%;

            transform: translateY(-50%);

            width: 17px;
            height: 17px;

            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 50%;

            background: transparent;

            color: white;

            font-size: 8px;

            cursor: pointer;
        }


        .social {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 7px;
        }

        .social-icons {
            display: flex;
            gap: 9px;
        }

        .social-icons a {
            width: 19px;
            height: 19px;

            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 50%;

            display: flex;
            justify-content: center;
            align-items: center;

            color: white;

            font-size: 8px;

            text-decoration: none;

            transition: 0.3s;
        }

        .social-icons a:hover {
            background: white;
            color: black;
        }


        .scroll-indicator {
            position: absolute;

            left: 50%;
            bottom: 3%;

            transform: translateX(-50%);

            width: 13px;
            height: 23px;

            border: 1.5px solid white;
            border-radius: 12px;

            display: flex;
            justify-content: center;

            padding-top: 4px;

            opacity: 0.8;
        }

        .scroll-dot {
            width: 3px;
            height: 5px;

            background: white;
            border-radius: 5px;

            animation: scrollMove 1.5s infinite;
        }

        @keyframes scrollMove {

            0% {
                transform: translateY(0);
                opacity: 1;
            }

            100% {
                transform: translateY(8px);
                opacity: 0;
            }

        }


        .right-line {
            position: absolute;

            right: 5px;
            bottom: 7%;

            width: 2px;
            height: 35px;

            background: linear-gradient(
                to bottom,
                transparent,
                rgba(255,255,255,0.8),
                transparent
            );
        }


        @media (max-width: 768px) {

            .header {
                top: 6%;
                left: 5%;
                right: 5%;
            }

            .logo {
                font-size: 19px;
            }

            .header-right {
                gap: 7px;
            }

            .shop-button {
                padding: 4px 8px;
                font-size: 7px;
            }

            .earth-glow {
                width: 90vw;
                height: 90vw;
            }

            .orbit-outer {
                width: 125vh;
                height: 125vh;
            }

            .orbit-middle {
                width: 80vh;
                height: 80vh;
            }

            .orbit-inner {
                width: 55vh;
                height: 55vh;
            }

            .center-content {
                top: 51%;
            }

            .center-content p {
                font-size: 8px;
                padding: 0 10px;
            }

            .footer {
                left: 5%;
                right: 5%;
                bottom: 13%;
            }

        }

        @media (max-width: 480px) {

            .menu-button {
                font-size: 8px;
            }

            .header-icon {
                width: 17px;
                height: 17px;
            }

            .shop-button {
                display: none;
            }

            .updates-title,
            .social-title {
                font-size: 7px;
            }

            .email-input {
                width: 125px;
            }

            .social-icons {
                gap: 5px;
            }

        }

        @media (max-height: 450px) {

            .header {
                top: 7%;
            }

            .footer {
                bottom: 8%;
            }

            .center-content {
                top: 53%;
            }

            .scroll-indicator {
                display: none;
            }

        }

    </style>

</head>

<body>

    <div class="space-background"></div>

    <div class="earth-glow"></div>

    <div class="orbit-container">

        <div class="orbit orbit-outer"></div>

        <div class="orbit orbit-middle"></div>

        <div class="orbit orbit-inner"></div>

    </div>

    <div id="canvas-container"></div>

    <div class="main-ui">
        <header class="header">

            <button class="menu-button">

                <span class="menu-icon">
                    <i class="fa-solid fa-bars"></i>
                </span>

                <span>Menu</span>

            </button>


            <div class="logo">
                QQ
            </div>


            <div class="header-right">

                <div class="header-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <div class="header-icon">
                    <i class="fa-regular fa-user"></i>
                </div>

                <button class="shop-button">
                    Shop with us
                </button>

            </div>

        </header>

        <main class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center pointer-events-auto w-full flex flex-col items-center">
            <div class="text-sm font-bold tracking-[0.5em] mb-4 text-blue-400">ASTROANALOGS</div>
            <h1 class="text-[32px] font-normal tracking-[0.15em] mb-2 drop-shadow-2xl text-shadow">EARTH TO MARS ANALOGS</h1>
            <p class="text-[11px] text-gray-300 font-light tracking-wide mb-8 drop-shadow-md">Identify Earth locations that analog the permanent Moon base locations and Mars.</p>
            <a href="/dashboard" class="border border-white rounded-full px-12 py-3 text-[11px] font-medium tracking-[0.2em] hover:bg-white hover:text-black transition duration-500 w-fit">
                GUEST LOGIN / EXPLORE
            </a>
        </main>

        <footer class="footer">

            <div class="updates">

                <span class="updates-title">
                    Get Updates
                </span>

                <form class="email-container"
                    onsubmit="event.preventDefault();">

                    <input
                        type="email"
                        class="email-input"
                        placeholder="Enter Email Address"
                        required
                    >

                    <button class="email-button" type="submit">

                        <i class="fa-regular fa-paper-plane"></i>

                    </button>

                </form>

            </div>

            <div class="social">

                <span class="social-title">
                    Follow QQ
                </span>

                <div class="social-icons">

                    <a href="#" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="#" aria-label="Twitter">
                        <i class="fa-brands fa-twitter"></i>
                    </a>

                    <a href="#" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                </div>

            </div>

        </footer>


        <div class="scroll-indicator">

            <div class="scroll-dot"></div>

        </div>

        <div class="right-line"></div>


    </div>

    <script type="module">

        import * as THREE from
            'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js';


        const container = document.getElementById(
            'canvas-container'
        );

        const scene = new THREE.Scene();

        const camera = new THREE.PerspectiveCamera(
            45,
            window.innerWidth / window.innerHeight,
            0.1,
            1000
        );

        camera.position.z = 4.6;

        const renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true
        });

        renderer.setSize(
            window.innerWidth,
            window.innerHeight
        );

        renderer.setPixelRatio(
            Math.min(window.devicePixelRatio, 2)
        );

        renderer.outputColorSpace = THREE.SRGBColorSpace;

        renderer.toneMapping = THREE.ACESFilmicToneMapping;

        renderer.toneMappingExposure = 1.15;

        container.appendChild(renderer.domElement);


        const earthGroup = new THREE.Group();

        scene.add(earthGroup);

        const earthGeometry = new THREE.SphereGeometry(
            1.15,
            96,
            96
        );

        const textureLoader = new THREE.TextureLoader();

        const earthTexture = textureLoader.load(
            'https://threejs.org/examples/textures/planets/earth_atmos_2048.jpg'
        );

        earthTexture.colorSpace = THREE.SRGBColorSpace;

        earthTexture.anisotropy =
            renderer.capabilities.getMaxAnisotropy();


        const earthMaterial = new THREE.MeshStandardMaterial({

            map: earthTexture,

            roughness: 0.85,

            metalness: 0.0

        });


        const earth = new THREE.Mesh(
            earthGeometry,
            earthMaterial
        );

        earthGroup.add(earth);
        const atmosphereGeometry = new THREE.SphereGeometry(
            1.23,
            96,
            96
        );

        const atmosphereMaterial = new THREE.ShaderMaterial({

            transparent: true,

            side: THREE.BackSide,

            blending: THREE.AdditiveBlending,

            depthWrite: false,

            vertexShader: `

                varying vec3 vNormal;

                void main() {

                    vNormal = normalize(normalMatrix * normal);

                    gl_Position = projectionMatrix *
                        modelViewMatrix *
                        vec4(position, 1.0);

                }

            `,

            fragmentShader: `

                varying vec3 vNormal;

                void main() {

                    float intensity = pow(
                        0.75 - dot(vNormal, vec3(0.0, 0.0, 1.0)),
                        3.0
                    );

                    gl_FragColor = vec4(
                        0.22,
                        0.57,
                        1.0,
                        intensity * 0.85
                    );

                }

            `

        });


        const atmosphere = new THREE.Mesh(
            atmosphereGeometry,
            atmosphereMaterial
        );

        earthGroup.add(atmosphere);

        const ambientLight = new THREE.AmbientLight(
            0xffffff,
            1.5
        );

        scene.add(ambientLight);


        const sunlight = new THREE.DirectionalLight(
            0xffffff,
            2.5
        );

        sunlight.position.set(-3, 2, 5);

        scene.add(sunlight);


        const blueLight = new THREE.PointLight(
            0x4b9fff,
            2.5,
            10
        );

        blueLight.position.set(-2, -2, 3);

        scene.add(blueLight);

        const starGeometry = new THREE.BufferGeometry();

        const starCount = 5000;

        const starPositions = new Float32Array(
            starCount * 3
        );

        for (let i = 0; i < starCount; i++) {

            const radius = 8 + Math.random() * 22;

            const theta = Math.random() * Math.PI * 2;

            const phi = Math.acos(
                2 * Math.random() - 1
            );

            starPositions[i * 3] =
                radius * Math.sin(phi) * Math.cos(theta);

            starPositions[i * 3 + 1] =
                radius * Math.sin(phi) * Math.sin(theta);

            starPositions[i * 3 + 2] =
                radius * Math.cos(phi);

        }


        starGeometry.setAttribute(
            'position',
            new THREE.BufferAttribute(starPositions, 3)
        );


        const starMaterial = new THREE.PointsMaterial({

            color: 0xffffff,

            size: 0.018,

            transparent: true,

            opacity: 0.65,

            sizeAttenuation: true

        });


        const stars = new THREE.Points(
            starGeometry,
            starMaterial
        );

        scene.add(stars);


        function animate() {

            requestAnimationFrame(animate);

            earth.rotation.y += 0.00055;

            earth.rotation.x += 0.00008;


            atmosphere.rotation.copy(earth.rotation);

            stars.rotation.y -= 0.000025;

            renderer.render(scene, camera);

        }

        animate();


        window.addEventListener('resize', () => {

            camera.aspect =
                window.innerWidth / window.innerHeight;

            camera.updateProjectionMatrix();

            renderer.setSize(
                window.innerWidth,
                window.innerHeight
            );

            renderer.setPixelRatio(
                Math.min(window.devicePixelRatio, 2)
            );

        });

    </script>

</body>

</html>
