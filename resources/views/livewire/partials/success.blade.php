<svg width="300" height="300" viewBox="0 0 200 200">
    <defs>
        <filter id="shadow">
            <feDropShadow dx="0" dy="4" stdDeviation="3" floodColor="#10B981" floodOpacity="0.5" />
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

        .check {
            stroke-dasharray: 200;
            stroke-dashoffset: 200;
            animation: draw 0.5s ease forwards 0.8s;
        }
    </style>

    <g class="icon">
        <circle cx="100" cy="105" r="70" fill="#059669" filter="url(#shadow)" />
        <circle cx="100" cy="100" r="70" fill="#10B981" />
        <path class="check" d="M65 100 L90 125 L135 80" stroke="white" stroke-width="12" stroke-linecap="round"
            stroke-linejoin="round" fill="none" />
    </g>
</svg>
