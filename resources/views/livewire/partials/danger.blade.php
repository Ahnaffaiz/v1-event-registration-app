<svg width="300" height="300" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <filter id="shadow">
            <feDropShadow dx="0" dy="4" stdDeviation="3" floodColor="#F43F5E" floodOpacity="0.5" />
        </filter>
    </defs>
    <style>
        @keyframes centerPopup {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .icon {
            transform-origin: center;
            animation: centerPopup 0.6s ease-out forwards, pulse 2s ease-in-out infinite 0.6s;
            cursor: pointer;
        }

        .icon:hover {
            animation: pulse 1s ease-in-out infinite;
        }

        .icon:active {
            animation: spin 0.5s ease-out;
        }

        .x-mark {
            stroke-dasharray: 200;
            stroke-dashoffset: 200;
            animation: draw 0.5s ease forwards 0.8s;
        }
    </style>

    <g class="icon">
        <circle cx="100" cy="105" r="70" fill="#E11D48" filter="url(#shadow)" />
        <circle cx="100" cy="100" r="70" fill="#F43F5E" />
        <path class="x-mark" d="M70 70 L130 130 M130 70 L70 130" stroke="white" stroke-width="12" stroke-linecap="round"
            stroke-linejoin="round" fill="none" />
    </g>
</svg>
