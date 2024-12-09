<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <style>
            /* CSS */
            .button-3 {
                appearance: none;
                background-color: #007bff; /* Primary Blue */
                border: 1px solid rgba(0, 123, 255, .15); /* Primary Border */
                border-radius: 6px;
                box-shadow: rgba(0, 123, 255, .1) 0 1px 0;
                box-sizing: border-box;
                color: #fff;
                cursor: pointer;
                display: inline-block;
                font-family: -apple-system,system-ui,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
                font-size: 14px;
                font-weight: 600;
                line-height: 20px;
                padding: 6px 16px;
                position: relative;
                text-align: center;
                text-decoration: none;
                user-select: none;
                -webkit-user-select: none;
                touch-action: manipulation;
                vertical-align: middle;
                white-space: nowrap;
            }

            .button-3:focus:not(:focus-visible):not(.focus-visible) {
                box-shadow: none;
                outline: none;
            }

            .button-3:hover {
                background-color: #0056b3; /* Darker Primary */
            }

            .button-3:focus {
                box-shadow: rgba(0, 123, 255, .4) 0 0 0 3px; /* Focus Outline */
                outline: none;
            }

            .button-3:disabled {
                background-color: #80bdff; /* Light Primary */
                border-color: rgba(0, 123, 255, .1);
                color: rgba(255, 255, 255, .8);
                cursor: default;
            }

            .button-3:active {
                background-color: #004085; /* Darkest Primary */
                box-shadow: rgba(0, 70, 130, .2) 0 1px 0 inset;
            }
        </style>
        
        <span class="text-gray-500">
            <button type="button" class="button-3">
                Lihat Map
            </button>
        </span>
    </div>
    
</x-dynamic-component>
