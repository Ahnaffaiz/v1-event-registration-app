<svg width="300" height="300" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <clipPath id="rounded-corners">
            <rect x="10" y="10" width="80" height="80" rx="15" ry="15" />
        </clipPath>
    </defs>

    <!-- QR Code shape with more rounded corners -->
    <g clip-path="url(#rounded-corners)">
        <rect x="10" y="10" width="80" height="80" fill="rgba(255,255,255,0.05)" />

        <!-- Outlined squares with rounded corners -->
        <rect x="20" y="20" width="20" height="20" fill="none" stroke="white" stroke-width="2" rx="5" ry="5" />
        <rect x="60" y="20" width="20" height="20" fill="none" stroke="white" stroke-width="2" rx="5" ry="5" />
        <rect x="20" y="60" width="20" height="20" fill="none" stroke="white" stroke-width="2" rx="5" ry="5" />
        <rect x="45" y="45" width="10" height="10" fill="none" stroke="white" stroke-width="2" rx="3" ry="3" />
    </g>

    <!-- Scanning line -->
    <line x1="10" y1="10" x2="90" y2="10" stroke="#10b981" stroke-width="2">
        <animateTransform attributeName="transform" type="translate" from="0 0" to="0 80" dur="2s"
            repeatCount="indefinite" />
        <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;0.1;0.9;1" dur="2s" repeatCount="indefinite" />
    </line>

    <!-- Corner markers with more rounded edges -->
    <path d="M10 25 Q10 10 25 10" stroke="white" stroke-width="4" fill="none" />
    <path d="M75 10 Q90 10 90 25" stroke="white" stroke-width="4" fill="none" />
    <path d="M90 75 Q90 90 75 90" stroke="white" stroke-width="4" fill="none" />
    <path d="M25 90 Q10 90 10 75" stroke="white" stroke-width="4" fill="none" />
</svg>
