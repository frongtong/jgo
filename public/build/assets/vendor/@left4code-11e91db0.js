(function(){let s=function o(n){return $("[data-loading-icon]").each(function(){let t=$(this).data("color")!==void 0?$(this).data("color"):$("body").css("color"),a=$(this).attr("class")!==void 0?$(this).attr("class"):"",r=[{name:"audio",svg:`
                    <svg width="15" viewBox="0 0 55 80" xmlns="http://www.w3.org/2000/svg" fill="${t}" class="${a}">
                        <g transform="matrix(1 0 0 -1 0 80)">
                            <rect width="10" height="20" rx="3">
                                <animate attributeName="height"
                                    begin="0s" dur="4.3s"
                                    values="20;45;57;80;64;32;66;45;64;23;66;13;64;56;34;34;2;23;76;79;20" calcMode="linear"
                                    repeatCount="indefinite" />
                            </rect>
                            <rect x="15" width="10" height="80" rx="3">
                                <animate attributeName="height"
                                    begin="0s" dur="2s"
                                    values="80;55;33;5;75;23;73;33;12;14;60;80" calcMode="linear"
                                    repeatCount="indefinite" />
                            </rect>
                            <rect x="30" width="10" height="50" rx="3">
                                <animate attributeName="height"
                                    begin="0s" dur="1.4s"
                                    values="50;34;78;23;56;23;34;76;80;54;21;50" calcMode="linear"
                                    repeatCount="indefinite" />
                            </rect>
                            <rect x="45" width="10" height="30" rx="3">
                                <animate attributeName="height"
                                    begin="0s" dur="2s"
                                    values="30;45;13;80;56;72;45;76;34;23;67;30" calcMode="linear"
                                    repeatCount="indefinite" />
                            </rect>
                        </g>
                    </svg>
                `},{name:"ball-triangle",svg:`
                    <svg width="20" viewBox="0 0 57 57" xmlns="http://www.w3.org/2000/svg" class="${a}">
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)">
                                <circle cx="5" cy="50" r="5" fill="${t}">
                                    <animate attributeName="cy"
                                        begin="0s" dur="2.2s"
                                        values="50;5;50;50"
                                        calcMode="linear"
                                        repeatCount="indefinite" />
                                    <animate attributeName="cx"
                                        begin="0s" dur="2.2s"
                                        values="5;27;49;5"
                                        calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="27" cy="5" r="5" fill="${t}">
                                    <animate attributeName="cy"
                                        begin="0s" dur="2.2s"
                                        from="5" to="5"
                                        values="5;50;50;5"
                                        calcMode="linear"
                                        repeatCount="indefinite" />
                                    <animate attributeName="cx"
                                        begin="0s" dur="2.2s"
                                        from="27" to="27"
                                        values="27;49;5;27"
                                        calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="49" cy="50" r="5" fill="${t}">
                                    <animate attributeName="cy"
                                        begin="0s" dur="2.2s"
                                        values="50;50;5;50"
                                        calcMode="linear"
                                        repeatCount="indefinite" />
                                    <animate attributeName="cx"
                                        from="49" to="49"
                                        begin="0s" dur="2.2s"
                                        values="49;5;27;49"
                                        calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                            </g>
                        </g>
                    </svg>
                `},{name:"bars",svg:`
                    <svg width="20" viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg" fill="${t}" class="${a}">
                        <rect y="10" width="15" height="120" rx="6">
                            <animate attributeName="height"
                                begin="0.5s" dur="1s"
                                values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                repeatCount="indefinite" />
                            <animate attributeName="y"
                                begin="0.5s" dur="1s"
                                values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
                                repeatCount="indefinite" />
                        </rect>
                        <rect x="30" y="10" width="15" height="120" rx="6">
                            <animate attributeName="height"
                                begin="0.25s" dur="1s"
                                values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                repeatCount="indefinite" />
                            <animate attributeName="y"
                                begin="0.25s" dur="1s"
                                values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
                                repeatCount="indefinite" />
                        </rect>
                        <rect x="60" width="15" height="140" rx="6">
                            <animate attributeName="height"
                                begin="0s" dur="1s"
                                values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                repeatCount="indefinite" />
                            <animate attributeName="y"
                                begin="0s" dur="1s"
                                values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
                                repeatCount="indefinite" />
                        </rect>
                        <rect x="90" y="10" width="15" height="120" rx="6">
                            <animate attributeName="height"
                                begin="0.25s" dur="1s"
                                values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                repeatCount="indefinite" />
                            <animate attributeName="y"
                                begin="0.25s" dur="1s"
                                values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
                                repeatCount="indefinite" />
                        </rect>
                        <rect x="120" y="10" width="15" height="120" rx="6">
                            <animate attributeName="height"
                                begin="0.5s" dur="1s"
                                values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                repeatCount="indefinite" />
                            <animate attributeName="y"
                                begin="0.5s" dur="1s"
                                values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
                                repeatCount="indefinite" />
                        </rect>
                    </svg>
                `},{name:"circles",svg:`
                    <svg width="20" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="${t}" class="${a}">
                        <path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
                            <animateTransform
                                attributeName="transform"
                                type="rotate"
                                from="0 67 67"
                                to="-360 67 67"
                                dur="2.5s"
                                repeatCount="indefinite"/>
                        </path>
                        <path d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
                            <animateTransform
                                attributeName="transform"
                                type="rotate"
                                from="0 67 67"
                                to="360 67 67"
                                dur="8s"
                                repeatCount="indefinite"/>
                        </path>
                    </svg>
                `},{name:"grid",svg:`
                    <svg width="20" viewBox="0 0 105 105" xmlns="http://www.w3.org/2000/svg" fill="${t}" class="${a}">
                        <circle cx="12.5" cy="12.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="0s" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="12.5" cy="52.5" r="12.5" fill-opacity=".5">
                            <animate attributeName="fill-opacity"
                            begin="100ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="52.5" cy="12.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="300ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="52.5" cy="52.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="600ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="92.5" cy="12.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="800ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="92.5" cy="52.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="400ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="12.5" cy="92.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="700ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="52.5" cy="92.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="500ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                        <circle cx="92.5" cy="92.5" r="12.5">
                            <animate attributeName="fill-opacity"
                            begin="200ms" dur="1s"
                            values="1;.2;1" calcMode="linear"
                            repeatCount="indefinite" />
                        </circle>
                    </svg>
                `},{name:"hearts",svg:`
                    <svg width="30" viewBox="0 0 140 64" xmlns="http://www.w3.org/2000/svg" fill="${t}" class="${a}">
                        <path d="M30.262 57.02L7.195 40.723c-5.84-3.976-7.56-12.06-3.842-18.063 3.715-6 11.467-7.65 17.306-3.68l4.52 3.76 2.6-5.274c3.717-6.002 11.47-7.65 17.305-3.68 5.84 3.97 7.56 12.054 3.842 18.062L34.49 56.118c-.897 1.512-2.793 1.915-4.228.9z" fill-opacity=".5">
                            <animate attributeName="fill-opacity"
                                begin="0s" dur="1.4s"
                                values="0.5;1;0.5"
                                calcMode="linear"
                                repeatCount="indefinite" />
                        </path>
                        <path d="M105.512 56.12l-14.44-24.272c-3.716-6.008-1.996-14.093 3.843-18.062 5.835-3.97 13.588-2.322 17.306 3.68l2.6 5.274 4.52-3.76c5.84-3.97 13.592-2.32 17.307 3.68 3.718 6.003 1.998 14.088-3.842 18.064L109.74 57.02c-1.434 1.014-3.33.61-4.228-.9z" fill-opacity=".5">
                            <animate attributeName="fill-opacity"
                                begin="0.7s" dur="1.4s"
                                values="0.5;1;0.5"
                                calcMode="linear"
                                repeatCount="indefinite" />
                        </path>
                        <path d="M67.408 57.834l-23.01-24.98c-5.864-6.15-5.864-16.108 0-22.248 5.86-6.14 15.37-6.14 21.234 0L70 16.168l4.368-5.562c5.863-6.14 15.375-6.14 21.235 0 5.863 6.14 5.863 16.098 0 22.247l-23.007 24.98c-1.43 1.556-3.757 1.556-5.188 0z" />
                    </svg>
                `},{name:"oval",svg:`
                    <svg width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="${t}" class="${a}">
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)" stroke-width="4">
                                <circle stroke-opacity=".5" cx="18" cy="18" r="18"/>
                                <path d="M36 18c0-9.94-8.06-18-18-18">
                                    <animateTransform
                                        attributeName="transform"
                                        type="rotate"
                                        from="0 18 18"
                                        to="360 18 18"
                                        dur="1s"
                                        repeatCount="indefinite"/>
                                </path>
                            </g>
                        </g>
                    </svg>
                `},{name:"puff",svg:`
                    <svg width="25" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="${t}" class="${a}">
                        <g fill="none" fill-rule="evenodd" stroke-width="4">
                            <circle cx="22" cy="22" r="1">
                                <animate attributeName="r"
                                    begin="0s" dur="1.8s"
                                    values="1; 20"
                                    calcMode="spline"
                                    keyTimes="0; 1"
                                    keySplines="0.165, 0.84, 0.44, 1"
                                    repeatCount="indefinite" />
                                <animate attributeName="stroke-opacity"
                                    begin="0s" dur="1.8s"
                                    values="1; 0"
                                    calcMode="spline"
                                    keyTimes="0; 1"
                                    keySplines="0.3, 0.61, 0.355, 1"
                                    repeatCount="indefinite" />
                            </circle>
                            <circle cx="22" cy="22" r="1">
                                <animate attributeName="r"
                                    begin="-0.9s" dur="1.8s"
                                    values="1; 20"
                                    calcMode="spline"
                                    keyTimes="0; 1"
                                    keySplines="0.165, 0.84, 0.44, 1"
                                    repeatCount="indefinite" />
                                <animate attributeName="stroke-opacity"
                                    begin="-0.9s" dur="1.8s"
                                    values="1; 0"
                                    calcMode="spline"
                                    keyTimes="0; 1"
                                    keySplines="0.3, 0.61, 0.355, 1"
                                    repeatCount="indefinite" />
                            </circle>
                        </g>
                    </svg>
                `},{name:"rings",svg:`
                    <svg width="30" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="${t}" class="${a}">
                        <g fill="none" fill-rule="evenodd" transform="translate(1 1)" stroke-width="3">
                            <circle cx="22" cy="22" r="6" stroke-opacity="0">
                                <animate attributeName="r"
                                    begin="1.5s" dur="3s"
                                    values="6;22"
                                    calcMode="linear"
                                    repeatCount="indefinite" />
                                <animate attributeName="stroke-opacity"
                                    begin="1.5s" dur="3s"
                                    values="1;0" calcMode="linear"
                                    repeatCount="indefinite" />
                                <animate attributeName="stroke-width"
                                    begin="1.5s" dur="3s"
                                    values="2;0" calcMode="linear"
                                    repeatCount="indefinite" />
                            </circle>
                            <circle cx="22" cy="22" r="6" stroke-opacity="0">
                                <animate attributeName="r"
                                    begin="3s" dur="3s"
                                    values="6;22"
                                    calcMode="linear"
                                    repeatCount="indefinite" />
                                <animate attributeName="stroke-opacity"
                                    begin="3s" dur="3s"
                                    values="1;0" calcMode="linear"
                                    repeatCount="indefinite" />
                                <animate attributeName="stroke-width"
                                    begin="3s" dur="3s"
                                    values="2;0" calcMode="linear"
                                    repeatCount="indefinite" />
                            </circle>
                            <circle cx="22" cy="22" r="8">
                                <animate attributeName="r"
                                    begin="0s" dur="1.5s"
                                    values="6;1;2;3;4;5;6"
                                    calcMode="linear"
                                    repeatCount="indefinite" />
                            </circle>
                        </g>
                    </svg>
                `},{name:"spinning-circles",svg:`
                    <svg width="20" viewBox="0 0 58 58" xmlns="http://www.w3.org/2000/svg" class="${a}">
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(2 1)" stroke="${t}" stroke-width="1.5">
                                <circle cx="42.601" cy="11.462" r="5" fill-opacity="1" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="1;0;0;0;0;0;0;0" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="49.063" cy="27.063" r="5" fill-opacity="0" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="0;1;0;0;0;0;0;0" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="42.601" cy="42.663" r="5" fill-opacity="0" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="0;0;1;0;0;0;0;0" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="27" cy="49.125" r="5" fill-opacity="0" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="0;0;0;1;0;0;0;0" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="11.399" cy="42.663" r="5" fill-opacity="0" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="0;0;0;0;1;0;0;0" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="4.938" cy="27.063" r="5" fill-opacity="0" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="0;0;0;0;0;1;0;0" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="11.399" cy="11.462" r="5" fill-opacity="0" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="0;0;0;0;0;0;1;0" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                                <circle cx="27" cy="5" r="5" fill-opacity="0" fill="${t}">
                                    <animate attributeName="fill-opacity"
                                        begin="0s" dur="1.3s"
                                        values="0;0;0;0;0;0;0;1" calcMode="linear"
                                        repeatCount="indefinite" />
                                </circle>
                            </g>
                        </g>
                    </svg>
                `},{name:"tail-spin",svg:`
                    <svg width="20" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" class="${a}">
                        <defs>
                            <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                                <stop stop-color="${t}" stop-opacity="0" offset="0%"/>
                                <stop stop-color="${t}" stop-opacity=".631" offset="63.146%"/>
                                <stop stop-color="${t}" offset="100%"/>
                            </linearGradient>
                        </defs>
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)">
                                <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="3">
                                    <animateTransform
                                        attributeName="transform"
                                        type="rotate"
                                        from="0 18 18"
                                        to="360 18 18"
                                        dur="0.9s"
                                        repeatCount="indefinite" />
                                </path>
                                <circle fill="${t}" cx="36" cy="18" r="1">
                                    <animateTransform
                                        attributeName="transform"
                                        type="rotate"
                                        from="0 18 18"
                                        to="360 18 18"
                                        dur="0.9s"
                                        repeatCount="indefinite" />
                                </circle>
                            </g>
                        </g>
                    </svg>
                `},{name:"three-dots",svg:`
                    <svg width="25" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="${t}" class="${a}">
                        <circle cx="15" cy="15" r="15">
                            <animate attributeName="r" from="15" to="15"
                                    begin="0s" dur="0.8s"
                                    values="15;9;15" calcMode="linear"
                                    repeatCount="indefinite" />
                            <animate attributeName="fill-opacity" from="1" to="1"
                                    begin="0s" dur="0.8s"
                                    values="1;.5;1" calcMode="linear"
                                    repeatCount="indefinite" />
                        </circle>
                        <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                            <animate attributeName="r" from="9" to="9"
                                    begin="0s" dur="0.8s"
                                    values="9;15;9" calcMode="linear"
                                    repeatCount="indefinite" />
                            <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                    begin="0s" dur="0.8s"
                                    values=".5;1;.5" calcMode="linear"
                                    repeatCount="indefinite" />
                        </circle>
                        <circle cx="105" cy="15" r="15">
                            <animate attributeName="r" from="15" to="15"
                                    begin="0s" dur="0.8s"
                                    values="15;9;15" calcMode="linear"
                                    repeatCount="indefinite" />
                            <animate attributeName="fill-opacity" from="1" to="1"
                                    begin="0s" dur="0.8s"
                                    values="1;.5;1" calcMode="linear"
                                    repeatCount="indefinite" />
                        </circle>
                    </svg>
                `}],e=this;r.forEach(function(i){$(e).data("loading-icon")==i.name&&$(e).replaceWith(i.svg)})}),o}();(function(){window.tailwind===void 0&&(window.tailwind={}),window.tailwind.svgLoader=function(){s()}})()})();(function(){$("body").on("click","[data-tw-toggle='collapse']",function(){s(this)});function s(n,t="toggle"){$(n).closest(".accordion").find("[data-tw-toggle='collapse']").each(function(){if(!$(this).hasClass("collapsed")&&this!==n&&(t=="toggle"||t=="hide")){const r=new Event("hide.tw.accordion");$(this).closest(".accordion-header")[0].dispatchEvent(r)}if($(this).hasClass("collapsed")&&this===n&&(t=="toggle"||t=="show")){const r=new Event("show.tw.accordion");$(this).closest(".accordion-header")[0].dispatchEvent(r)}});const a=$(n).hasClass("collapsed");$(n).closest(".accordion").find(".accordion-collapse").slideUp(300,r=>{$(r).removeClass("show"),$(r).closest(".accordion-item").find("[data-tw-toggle='collapse']").addClass("collapsed").attr("aria-expanded",!1)}),setTimeout(()=>{const r=$(n).closest(".accordion-item").find(".accordion-collapse"),e=()=>{$(r).removeClass("show"),$(n).addClass("collapsed").attr("aria-expanded",!1),$(n).closest(".accordion-item").find(".accordion-collapse").slideUp()},i=()=>{$(r).addClass("show"),$(n).removeClass("collapsed").attr("aria-expanded",!0),$(n).closest(".accordion-item").find(".accordion-collapse").slideDown()};t==="toggle"?a?i():e():t==="show"?i():e()},300)}function o(n){return{show(){s(n,"show")},hide(){s(n,"hide")},toggle(){s(n)}}}(function(){$("[data-tw-toggle='collapse']").each(function(){$(this).closest(".accordion-header")[0].__accordion=o(this)}),window.tailwind===void 0&&(window.tailwind={}),window.tailwind.Accordion={getInstance(t){return t.__accordion},getOrCreateInstance(t){return t.__accordion===void 0?o(t):t.__accordion}}})()})();(function(){$("body").on("click","[data-tw-dismiss='alert']",function(){o($(this).closest(".alert"))});function s(a){$(a).fadeIn(300,function(){$(this).addClass("show");const e=new Event("shown.tw.alert");$(a)[0].dispatchEvent(e)});const r=new Event("show.tw.alert");$(a)[0].dispatchEvent(r)}function o(a){$(a).fadeOut(300,function(){$(this).removeClass("show");const e=new Event("hidden.tw.alert");$(a)[0].dispatchEvent(e)});const r=new Event("hide.tw.alert");$(a)[0].dispatchEvent(r)}function n(a){$(a).hasClass("show")?o(a):s(a)}function t(a){return{show(){s(a)},hide(){o(a)},toggle(){n(a)}}}(function(){$(".alert").each(function(){this.__alert=t(this)}),window.tailwind===void 0&&(window.tailwind={}),window.tailwind.Alert={getInstance(r){return r.__alert},getOrCreateInstance(r){return r.__alert===void 0?t(r):r.__alert}}})()})();(function(){function s(){$(".dropdown-menu").each(async function(){if($(this).attr("id")!==void 0&&$('[data-dropdown-replacer="'+$(this).attr("id")+'"]').length&&$(this).data("dropdown-programmatically")===void 0){let e=$(this).attr("id"),i=$('[data-dropdown-replacer="'+e+'"]').parent().find("[data-tw-toggle='dropdown']");$(this).removeClass("show");const c=new Event("hide.tw.dropdown");$(i).parent()[0].dispatchEvent(c),await setTimeout(()=>{$('[data-dropdown-replacer="'+e+'"]').replaceWith(this),$(this).removeAttr("style"),$(this).removeAttr("data-popper-placement"),$(i).attr("aria-expanded",!1);const d=new Event("hidden.tw.dropdown");$(i).parent()[0].dispatchEvent(d)},200)}else $(this).attr("id")!==void 0&&!$('[data-dropdown-replacer="'+$(this).attr("id")+'"]').length&&$(this).hasClass("show")&&$(this).data("dropdown-programmatically")===void 0?$(this).remove():$(this).data("dropdown-programmatically")=="initiate"?$(this).attr("data-dropdown-programmatically","showed"):$(this).data("dropdown-programmatically")=="showed"&&($(this).removeAttr("data-dropdown-programmatically"),s())})}function o(e){return e.filter((i,c)=>c.offsetParent!==null)}async function n(e){let i=$(e).find(".dropdown-menu").first(),c=o($(e).find("[data-tw-toggle='dropdown']")),d=$(e).data("tw-placement")?$(e).data("tw-placement"):"bottom-end",l="_"+Math.random().toString(36).substr(2,9);if(s(),$(i).length){$(c).attr("aria-expanded",!0),$(e).css("position")=="static"&&$(e).css("position","relative"),$(i).show(),$(i).css("width",$(i).css("width")),$('<div data-dropdown-replacer="'+l+'"></div>').insertAfter(i),$(i).attr("id",l).appendTo("body"),$(".modal.show").each(function(){$(this).find('[data-dropdown-replacer="'+l+'"]')&&$(i).css("z-index",$(this).css("z-index"))}),Popper.createPopper(c[0],i[0],{placement:d}),$(i).addClass("show");const u=new Event("show.tw.dropdown");$(e)[0].dispatchEvent(u),await setTimeout(()=>{const f=new Event("shown.tw.dropdown");$(e)[0].dispatchEvent(f)},200)}}function t(e){let i=$(e).find(".dropdown-menu").first();$(i).length?a(e):s()}function a(e){if($(e).find(".dropdown-menu").length)$(e).find(".dropdown-menu").attr("data-dropdown-programmatically","initiate");else{let i=$("[data-dropdown-replacer]").data("dropdown-replacer");$("#"+i).attr("data-dropdown-programmatically","initiate")}n(e)}function r(e){const i=$(e).closest(".dropdown");return{show(){a(i)},hide(){s()},toggle(){t(i)}}}$("body").on("click",function(e){let i=$(e.target).closest(".dropdown"),c=$(i).find("[data-tw-toggle='dropdown']"),d=$(i).find(".dropdown-menu").first(),l=$(e.target).closest(".dropdown-menu").first(),u=$(e.target).data("tw-dismiss");!$(i).length&&!$(l).length||$(c).length&&!$(d).length||u=="dropdown"?s():$(l).length||n(i)}),document.addEventListener("keydown",function(e){e.code=="Escape"&&s()}),function(){$("[data-tw-toggle='dropdown']").each(function(){this.__dropdown=r(this)}),window.tailwind===void 0&&(window.tailwind={}),window.tailwind.Dropdown={getInstance(i){return i.__dropdown},getOrCreateInstance(i){return i.__dropdown===void 0?r(i):i.__dropdown}}}()})();(function(){function s(){let e=9999;return $(".modal").each(function(){$(this).css("z-index")!=="auto"&&$(this).css("z-index")>e&&(e=parseInt($(this).css("z-index")))}),e}function o(e){return window.innerWidth-$(e)[0].clientWidth}function n(e){if(!$("[data-modal-replacer='"+$(e).attr("id")+"']").length){$('<div data-modal-replacer="'+$(e).attr("id")+'"></div>').insertAfter(e),$(e).css({"margin-top":0,"margin-left":0}),$(e).attr("aria-hidden",!1).appendTo("body"),setTimeout(()=>{$(e).addClass("show").css("z-index",s()+1);const c=new Event("shown.tw.modal");$(e)[0].dispatchEvent(c)},200),$("body").css("padding-right",parseInt($("body").css("padding-right"))+o("html")+"px").addClass("overflow-y-hidden"),$(".modal").removeClass("overflow-y-auto").css("padding-left","0px"),$(e).addClass("overflow-y-auto").css("padding-left",o(e)+"px").addClass($(".modal.show").length?"modal-overlap":"");const i=new Event("show.tw.modal");$(e)[0].dispatchEvent(i)}}function t(e){if($(e).hasClass("modal")&&$(e).hasClass("show")){let i=parseFloat($(e).css("transition-duration").split(",")[1])*1e3;$(e).attr("aria-hidden",!0).removeClass("show"),setTimeout(()=>{$(e).removeAttr("style").removeClass("modal-overlap").removeClass("overflow-y-auto"),$(".modal").each(function(){parseInt($(this).css("z-index"))===s()&&$(this).addClass("overflow-y-auto").css("padding-left",o(this)+"px")}),s()==9999&&$("body").removeClass("overflow-y-hidden").css("padding-right",""),$('[data-modal-replacer="'+$(e).attr("id")+'"]').replaceWith(e);const d=new Event("hidden.tw.modal");$(e)[0].dispatchEvent(d)},i);const c=new Event("hide.tw.modal");$(e)[0].dispatchEvent(c)}}function a(e){$(e).hasClass("modal")&&$(e).hasClass("show")?t(e):n(e)}function r(e){return{show(){n(e)},hide(){t(e)},toggle(){a(e)}}}$("body").on("click",'[data-tw-toggle="modal"]',function(){n($(this).attr("data-tw-target"))}),$("body").on("click",e=>{$(e.target).hasClass("modal")&&$(e.target).hasClass("show")&&($(e.target).data("tw-backdrop")!=="static"?t(e.target):($(e.target).addClass("modal-static"),setTimeout(()=>{$(e.target).removeClass("modal-static")},600)))}),$("body").on("click",'[data-tw-dismiss="modal"]',function(){let e=$(this).closest(".modal")[0];t(e)}),document.addEventListener("keydown",e=>{if(e.code=="Escape"){let i=$(".modal.show").last();$(i).hasClass("modal")&&$(i).hasClass("show")&&($(i).data("tw-keyboard")===void 0||$(i).data("tw-keyboard")!=="false")&&($(i).data("tw-backdrop")!=="static"?t(i):($(i).addClass("modal-static"),setTimeout(()=>{$(i).removeClass("modal-static")},600)))}}),function(){$(".modal").each(function(){this.__modal=r(this)}),window.tailwind===void 0&&(window.tailwind={}),window.tailwind.Modal={getInstance(i){return i.__modal},getOrCreateInstance(i){return i.__modal===void 0?r(i):i.__modal}}}()})();(function(){$("body").on("click","[role='tab']",function(){s(this)});function s(n){$(n).closest("[role='tablist']").find("[role='tab']").each(function(){if($(this).hasClass("active")&&this!==n){const r=new Event("hide.tw.tab");$(this)[0].dispatchEvent(r)}if(!$(this).hasClass("active")&&this===n){const r=new Event("show.tw.tab");$(this)[0].dispatchEvent(r)}}),$(n).closest("[role='tablist']").find("[role='tab']").removeClass("active").attr("aria-selected",!1),$(n).addClass("active").attr("aria-selected",!0);let t=$(n).attr("data-tw-target"),a=$(t).closest(".tab-content").width();$(t).closest(".tab-content").children(".tab-pane").removeAttr("style").removeClass("active"),$(t).css("width",a+"px").addClass("active")}(function(t){addEventListener("resize",a=>{$("[role='tabpanel']").each(function(){if($(this).hasClass("active")){let r=$(this).closest(".tab-content").width();$(this).css("width",r+"px")}})})})();function o(n){return{show(){s(n)}}}(function(){$("[role='tab']").each(function(){this.__tab=o(this)}),window.tailwind===void 0&&(window.tailwind={}),window.tailwind.Tab={getInstance(t){return t.__tab},getOrCreateInstance(t){return t.__tab===void 0?o(t):t.__tab}}})()})();
