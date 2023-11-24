"use strict";

function ownKeys(e, t) {
    var r, o = Object.keys(e);
    return Object.getOwnPropertySymbols && (r = Object.getOwnPropertySymbols(e), t && (r = r.filter(function (t) {
        return Object.getOwnPropertyDescriptor(e, t).enumerable
    })), o.push.apply(o, r)), o
}

function _objectSpread(e) {
    for (var t = 1; t < arguments.length; t++) {
        var r = null != arguments[t] ? arguments[t] : {};
        t % 2 ? ownKeys(Object(r), !0).forEach(function (t) {
            _defineProperty(e, t, r[t])
        }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(r)) : ownKeys(Object(r)).forEach(function (t) {
            Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(r, t))
        })
    }
    return e
}

function _defineProperty(t, e, r) {
    return e in t ? Object.defineProperty(t, e, {
        value: r,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : t[e] = r, t
}

function _typeof(t) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
        return typeof t
    } : function (t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    })(t)
}

var docReady = function (t) {
    "loading" === document.readyState ? document.addEventListener("DOMContentLoaded", t) : setTimeout(t, 1)
}, resize = function (t) {
    return window.addEventListener("resize", t)
}, isIterableArray = function (t) {
    return Array.isArray(t) && !!t.length
}, camelize = function (t) {
    t = t.replace(/[-_\s.]+(.)?/g, function (t, e) {
        return e ? e.toUpperCase() : ""
    });
    return "".concat(t.substr(0, 1).toLowerCase()).concat(t.substr(1))
}, getData = function (e, r) {
    try {
        return JSON.parse(e.dataset[camelize(r)])
    } catch (t) {
        return e.dataset[camelize(r)]
    }
}, hexToRgb = function (t) {
    t = 0 === t.indexOf("#") ? t.substring(1) : t, t = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i, function (t, e, r, o) {
        return e + e + r + r + o + o
    }));
    return t ? [parseInt(t[1], 16), parseInt(t[2], 16), parseInt(t[3], 16)] : null
}, rgbaColor = function () {
    var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : "#fff",
        e = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : .5;
    return "rgba(".concat(hexToRgb(t), ", ").concat(e, ")")
}, getColor = function (t) {
    var e = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : document.documentElement;
    return getComputedStyle(e).getPropertyValue("--falcon-".concat(t)).trim()
}, getColors = function (t) {
    return {
        primary: getColor("primary", t),
        secondary: getColor("secondary", t),
        success: getColor("success", t),
        info: getColor("info", t),
        warning: getColor("warning", t),
        danger: getColor("danger", t),
        light: getColor("light", t),
        dark: getColor("dark", t)
    }
}, getSubtleColors = function (t) {
    return {
        primary: getColor("primary-bg-subtle", t),
        secondary: getColor("secondary-bg-subtle", t),
        success: getColor("success-bg-subtle", t),
        info: getColor("info-bg-subtle", t),
        warning: getColor("warning-bg-subtle", t),
        danger: getColor("danger-bg-subtle", t),
        light: getColor("light-bg-subtle", t),
        dark: getColor("dark-bg-subtle", t)
    }
}, getGrays = function (t) {
    return {
        white: getColor("gray-white", t),
        100: getColor("gray-100", t),
        200: getColor("gray-200", t),
        300: getColor("gray-300", t),
        400: getColor("gray-400", t),
        500: getColor("gray-500", t),
        600: getColor("gray-600", t),
        700: getColor("gray-700", t),
        800: getColor("gray-800", t),
        900: getColor("gray-900", t),
        1e3: getColor("gray-1000", t),
        1100: getColor("gray-1100", t),
        black: getColor("gray-black", t)
    }
}, hasClass = function (t, e) {
    return t.classList.value.includes(e)
}, addClass = function (t, e) {
    t.classList.add(e)
}, removeClass = function (t, e) {
    t.classList.remove(e)
}, getOffset = function (t) {
    var t = t.getBoundingClientRect(), e = window.pageXOffset || document.documentElement.scrollLeft,
        r = window.pageYOffset || document.documentElement.scrollTop;
    return {top: t.top + r, left: t.left + e}
};

function isScrolledIntoView(t) {
    var t = t.getBoundingClientRect(), e = window.innerHeight || document.documentElement.clientHeight,
        r = window.innerWidth || document.documentElement.clientWidth, e = t.top <= e && 0 <= t.top + t.height,
        r = t.left <= r && 0 <= t.left + t.width;
    return e && r
}

var breakpoints = {xs: 0, sm: 576, md: 768, lg: 992, xl: 1200, xxl: 1540}, getBreakpoint = function (t) {
        var e, t = t && t.classList.value;
        return e = t ? breakpoints[t.split(" ").filter(function (t) {
            return t.includes("navbar-expand-")
        }).pop().split("-").pop()] : e
    }, setCookie = function (t, e, r) {
        var o = new Date;
        o.setTime(o.getTime() + r), document.cookie = "".concat(t, "=").concat(e, ";expires=").concat(o.toUTCString())
    }, getCookie = function (t) {
        t = document.cookie.match("(^|;) ?".concat(t, "=([^;]*)(;|$)"));
        return t && t[2]
    }, settings = {tinymce: {theme: "oxide"}, chart: {borderColor: "rgba(255, 255, 255, 0.8)"}},
    newChart = function (t, e) {
        t = t.getContext("2d");
        return new window.Chart(t, e)
    }, getItemFromStore = function (e, r) {
        var o = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : localStorage;
        try {
            return JSON.parse(o.getItem(e)) || r
        } catch (t) {
            return o.getItem(e) || r
        }
    }, setItemToStore = function (t, e) {
        return (2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : localStorage).setItem(t, e)
    }, getStoreSpace = function () {
        var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : localStorage;
        return parseFloat((escape(encodeURIComponent(JSON.stringify(t))).length / 1048576).toFixed(2))
    }, getDates = function (r, t) {
        var o = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : 864e5;
        return Array.from({length: 1 + (t - r) / o}, function (t, e) {
            return new Date(r.valueOf() + o * e)
        })
    }, getPastDates = function (t) {
        var e;
        switch (t) {
            case"week":
                e = 7;
                break;
            case"month":
                e = 30;
                break;
            case"year":
                e = 365;
                break;
            default:
                e = t
        }
        var r = new Date, o = r, r = new Date((new Date).setDate(r.getDate() - (e - 1)));
        return getDates(r, o)
    }, getRandomNumber = function (t, e) {
        return Math.floor(Math.random() * (e - t) + t)
    }, utils = {
        docReady: docReady,
        breakpoints: breakpoints,
        resize: resize,
        isIterableArray: isIterableArray,
        camelize: camelize,
        getData: getData,
        hasClass: hasClass,
        addClass: addClass,
        hexToRgb: hexToRgb,
        rgbaColor: rgbaColor,
        getColor: getColor,
        getColors: getColors,
        getSubtleColors: getSubtleColors,
        getGrays: getGrays,
        getOffset: getOffset,
        isScrolledIntoView: isScrolledIntoView,
        getBreakpoint: getBreakpoint,
        setCookie: setCookie,
        getCookie: getCookie,
        newChart: newChart,
        settings: settings,
        getItemFromStore: getItemFromStore,
        setItemToStore: setItemToStore,
        getStoreSpace: getStoreSpace,
        getDates: getDates,
        getPastDates: getPastDates,
        getRandomNumber: getRandomNumber,
        removeClass: removeClass
    }, getPosition = function (t, e, r, o, a) {
        return {top: t[1] - a.contentSize[1] - 10, left: t[0] - a.contentSize[0] / 2}
    }, echartSetOption = function (e, r, o) {
        var t = document.body;
        e.setOption(window._.merge(o(), r)), t.addEventListener("clickControl", function (t) {
            "theme" === t.detail.control && e.setOption(window._.merge(o(), r))
        })
    }, tooltipFormatter = function (t) {
        var e = "";
        return t.forEach(function (t) {
            e += '<div class=\'ms-1\'>\n        <h6 class="text-700"><span class="fas fa-circle me-1 fs--2" style="color:'.concat(t.borderColor || t.color, '"></span>\n          ').concat(t.seriesName, " : ").concat("object" === _typeof(t.value) ? t.value[1] : t.value, "\n        </h6>\n      </div>")
        }), "<div>\n            <p class='mb-2 text-600'>\n              ".concat(window.dayjs(t[0].axisValue).isValid() ? window.dayjs(t[0].axisValue).format("MMMM DD") : t[0].axisValue, "\n            </p>\n            ").concat(e, "\n          </div>")
    }, echartsAreaPiecesChartInit = function () {
        var t, e = document.querySelector(".echart-area-pieces-chart-example");
        e && (t = utils.getData(e, "options"), e = window.echarts.init(e), echartSetOption(e, t, function () {
            return {
                tooltip: {
                    trigger: "axis",
                    padding: [7, 10],
                    backgroundColor: utils.getGrays()[100],
                    borderColor: utils.getGrays()[300],
                    textStyle: {color: utils.getColors().dark},
                    borderWidth: 1,
                    transitionDuration: 0,
                    position: function (t, e, r, o, a) {
                        return getPosition(t, e, r, o, a)
                    },
                    axisPointer: {type: "none"},
                    formatter: tooltipFormatter
                },
                xAxis: {
                    type: "category",
                    boundaryGap: !1,
                    axisLine: {lineStyle: {color: utils.getGrays()[300], type: "solid"}},
                    axisTick: {show: !1},
                    axisLabel: {
                        color: utils.getGrays()[400], margin: 15, formatter: function (t) {
                            return window.dayjs(t).format("MMM DD")
                        }
                    },
                    splitLine: {show: !1}
                },
                yAxis: {
                    type: "value",
                    splitLine: {lineStyle: {color: utils.getGrays()[200]}},
                    boundaryGap: !1,
                    axisLabel: {show: !0, color: utils.getGrays()[400], margin: 15},
                    axisTick: {show: !1},
                    axisLine: {show: !1}
                },
                visualMap: {
                    type: "piecewise",
                    show: !1,
                    dimension: 0,
                    seriesIndex: 0,
                    pieces: [{gt: 1, lt: 3, color: utils.rgbaColor(utils.getColor("primary"), .4)}, {
                        gt: 5,
                        lt: 7,
                        color: utils.rgbaColor(utils.getColor("primary"), .4)
                    }]
                },
                series: [{
                    type: "line",
                    name: "Total",
                    smooth: .6,
                    symbol: "none",
                    lineStyle: {color: utils.getColor("primary"), width: 5},
                    markLine: {
                        symbol: ["none", "none"],
                        label: {show: !1},
                        data: [{xAxis: 1}, {xAxis: 3}, {xAxis: 5}, {xAxis: 7}]
                    },
                    areaStyle: {},
                    data: [["2019-10-10", 200], ["2019-10-11", 560], ["2019-10-12", 750], ["2019-10-13", 580], ["2019-10-14", 250], ["2019-10-15", 300], ["2019-10-16", 450], ["2019-10-17", 300], ["2019-10-18", 100]]
                }],
                grid: {right: 20, left: 5, bottom: 5, top: 8, containLabel: !0}
            }
        }))
    }, echartsDoughnutChartInit = function () {
        var t, e = document.querySelector(".echart-doughnut-chart-example");
        e && (t = utils.getData(e, "options"), e = window.echarts.init(e), echartSetOption(e, t, function () {
            return {
                legend: {left: "left", textStyle: {color: utils.getGrays()[600]}},
                series: [{
                    type: "pie",
                    radius: ["40%", "70%"],
                    center: ["50%", "55%"],
                    avoidLabelOverlap: !1,
                    label: {show: !1, position: "center"},
                    labelLine: {show: !1},
                    data: [{value: 1048, name: "Facebook", itemStyle: {color: utils.getColor("primary")}}, {
                        value: 735,
                        name: "Youtube",
                        itemStyle: {color: utils.getColor("danger")}
                    }, {value: 580, name: "Twitter", itemStyle: {color: utils.getColor("info")}}, {
                        value: 484,
                        name: "Linkedin",
                        itemStyle: {color: utils.getColor("success")}
                    }, {value: 300, name: "Github", itemStyle: {color: utils.getColor("warning")}}]
                }],
                tooltip: {
                    trigger: "item",
                    padding: [7, 10],
                    backgroundColor: utils.getGrays()[100],
                    borderColor: utils.getGrays()[300],
                    textStyle: {color: utils.getColors().dark},
                    borderWidth: 1,
                    transitionDuration: 0,
                    axisPointer: {type: "none"}
                }
            }
        }))
    }, echartsDoughnutRoundedChartInit = function () {
        var t, e, r = document.querySelector(".echart-doughnut-rounded-chart");
        r && (t = utils.getData(r, "options"), e = window.echarts.init(r), echartSetOption(e, t, function () {
            return {
                legend: {orient: "vertical", left: "left", textStyle: {color: utils.getGrays()[600]}},
                series: [{
                    type: "pie",
                    radius: ["40%", "70%"],
                    center: window.innerWidth < 530 ? ["65%", "55%"] : ["50%", "55%"],
                    avoidLabelOverlap: !1,
                    itemStyle: {borderRadius: 10, borderColor: utils.getGrays()[100], borderWidth: 2},
                    label: {show: !1, position: "center"},
                    labelLine: {show: !1},
                    data: [{value: 1048, name: "Starter", itemStyle: {color: utils.getColor("primary")}}, {
                        value: 735,
                        name: "Basic",
                        itemStyle: {color: utils.getColor("danger")}
                    }, {value: 580, name: "Optimal", itemStyle: {color: utils.getColor("info")}}, {
                        value: 484,
                        name: "Business",
                        itemStyle: {color: utils.getColor("success")}
                    }, {value: 300, name: "Premium", itemStyle: {color: utils.getColor("warning")}}]
                }],
                tooltip: {
                    trigger: "item",
                    padding: [7, 10],
                    backgroundColor: utils.getGrays()[100],
                    borderColor: utils.getGrays()[300],
                    textStyle: {color: utils.getColors().dark},
                    borderWidth: 1,
                    transitionDuration: 0,
                    axisPointer: {type: "none"}
                }
            }
        }), utils.resize(function () {
            window.innerWidth < 530 ? e.setOption({series: [{center: ["65%", "55%"]}]}) : e.setOption({series: [{center: ["50%", "55%"]}]})
        }))
    };

docReady(echartsAreaPiecesChartInit), docReady(echartsDoughnutChartInit), docReady(echartsDoughnutRoundedChartInit);
//# sourceMappingURL=echart-example.js.map
