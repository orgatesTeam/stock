/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 49);
/******/ })
/************************************************************************/
/******/ ({

/***/ 1:
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(50);


/***/ }),

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

Vue.component('warehouse-table', __webpack_require__(51));
Vue.component('warehouse-deal-table', __webpack_require__(54));
Vue.component('pagination', __webpack_require__(57));

window.vm = new Vue({
    el: '#app',
    data: {
        warehouse: {
            'stockIDs': {},
            'currentPage': 1,
            'currentStockID': '',
            'items': {},
            'pagination': '',
            'stockCurrentPrice': {}
        },
        dealWarehouse: {
            'stockIDs': {},
            'currentPage': 1,
            'currentStockID': '',
            'items': {},
            'pagination': ''
        },
        soldWarehouses: {}
    },
    mounted: function mounted() {
        this.selectedWarehouseStockID = this.cookieWarehouseStockID;
        this.selectedDealWarehouseStockID = this.cookieDealWarehouseStockID;
    },
    methods: {
        getWarehousePage: function getWarehousePage(page) {
            this.warehouse.currentPage = page;
            this.setWarehouseItems();
        },
        getDealWarehousePage: function getDealWarehousePage(page) {
            this.dealWarehouse.currentPage = page;
            this.setDealWarehouseItems();
        },
        warehouseSoldModal: function warehouseSoldModal(soldWarehouses) {
            this.soldWarehouses = soldWarehouses;
        },
        setWarehouseItems: function setWarehouseItems() {
            var _this = this;

            var url = this.warehouseUrl;
            this.warehouses = {};
            window.axios.get(url).then(function (response) {
                if (response.status === 200) {
                    items = response.data.items;
                    console.log(items.items);
                    _this.warehouse.stockIDs = items.stockIDs;
                    _this.warehouse.items = items.items;
                    _this.warehouse.pagination = items.pagination;
                    _this.warehouse.stockCurrentPrice = items.stockCurrentPrice;
                }
            });
        },
        setDealWarehouseItems: function setDealWarehouseItems() {
            var _this2 = this;

            var url = this.dealWarehouseUrl;
            this.warehouses = {};
            window.axios.get(url).then(function (response) {
                if (response.status === 200) {
                    items = response.data.items;
                    _this2.dealWarehouse.stockIDs = items.stockIDs;
                    _this2.dealWarehouse.items = items.items;
                    _this2.dealWarehouse.pagination = items.pagination;
                }
            });
        }
    },
    computed: {
        buyStockID: {
            get: function get() {
                return $.cookie("buyStockID");
            },
            set: function set($stockID) {
                $.cookie("buyStockID", $stockID);
            }
        },
        cookieWarehouseStockID: {
            get: function get() {
                return $.cookie("warehouseStockID");
            },
            set: function set(stockID) {
                $.cookie("warehouseStockID", stockID);
            }
        },
        cookieDealWarehouseStockID: {
            get: function get() {
                return $.cookie("dealWarehouseStockID");
            },
            set: function set(stockID) {
                $.cookie("dealWarehouseStockID", stockID);
            }
        },
        warehouseUrl: function warehouseUrl() {
            var page = 'page=' + this.warehouse.currentPage;
            var stockID = 'stockID=' + this.selectedWarehouseStockID;
            return $('#ajaxWarehouseRecordUrl').val() + '?' + page + '&' + stockID;
        },
        dealWarehouseUrl: function dealWarehouseUrl() {
            var page = 'page=' + this.dealWarehouse.currentPage;
            var stockID = 'stockID=' + this.selectedDealWarehouseStockID;
            return $('#ajaxWarehouseDealRecordUrl').val() + '?' + page + '&' + stockID;
        },
        selectedWarehouseStockID: {
            get: function get() {
                return this.warehouse.currentStockID === undefined ? 'all' : this.warehouse.currentStockID;
            },
            set: function set(stockID) {
                this.warehouse.currentPage = 1;
                this.warehouse.currentStockID = stockID;
                this.cookieWarehouseStockID = stockID;
                this.setWarehouseItems();
            }
        },
        selectedDealWarehouseStockID: {
            get: function get() {
                return this.dealWarehouse.currentStockID === undefined ? 'all' : this.dealWarehouse.currentStockID;
            },
            set: function set(stockID) {
                this.dealWarehouse.currentPage = 1;
                this.dealWarehouse.currentStockID = stockID;
                this.cookieDealWarehouseStockID = stockID;
                this.setDealWarehouseItems();
            }
        }
    }
});

/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(52)
/* template */
var __vue_template__ = __webpack_require__(53)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/js/components/WarehouseTable.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5f05bb42", Component.options)
  } else {
    hotAPI.reload("data-v-5f05bb42", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 52:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['warehouse', 'sold'],
    data: function data() {
        return {
            check: true,
            checkedWarehouses: {}
        };
    },

    methods: {
        setWarehouses: function setWarehouses() {
            var checkedWarehouses = {};
            var warehouses = this.warehouse.items;
            $('input:checkbox:checked[name="warehouseCheckbox[]"]').each(function (i) {
                checkedWarehouses[this.value] = warehouses[this.value];
            });
            this.checkedWarehouses = checkedWarehouses;
        },
        warehouseCheckboxAll: function warehouseCheckboxAll() {
            $("input[name='warehouseCheckbox[]']").prop("checked", this.check);
            this.check = !this.check;
            this.setWarehouses();
        },
        tdChecked: function tdChecked(id) {
            var checked = $("input[value='" + id + "']").prop('checked');
            $("input[value='" + id + "']").prop('checked', !checked);
            this.setWarehouses();
        },
        showStockName: function showStockName(stockID) {
            return this.warehouse.stockIDs[stockID];
        }
    },
    computed: {
        showSoldButton: function showSoldButton() {
            return Object.keys(this.checkedWarehouses).length > 0;
        },
        showCheckboxAllName: function showCheckboxAllName() {
            return this.check ? '全選' : '反選';
        }
    }
});

/***/ }),

/***/ 53:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("h3", { staticClass: "red-font" }, [_vm._v("股票價格")]),
    _vm._v(" "),
    _c(
      "table",
      {
        staticClass:
          "table table-striped table-bordered table-responsive table-h3"
      },
      [
        _vm._m(0),
        _vm._v(" "),
        _c(
          "tbody",
          _vm._l(_vm.warehouse.stockCurrentPrice, function(warehouse, id) {
            return _c("tr", [
              _c("td", [_vm._v(_vm._s(_vm.showStockName(id)))]),
              _vm._v(" "),
              _c("td", [_vm._v(_vm._s(warehouse))])
            ])
          })
        )
      ]
    ),
    _vm._v(" "),
    _c("h3", { staticClass: "red-font" }, [_vm._v("庫存量")]),
    _vm._v(" "),
    _c(
      "button",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.showSoldButton,
            expression: "showSoldButton"
          }
        ],
        staticClass: "btn btn-danger bottom-s btn-lg",
        attrs: {
          type: "button",
          "data-toggle": "modal",
          "data-target": "#myModal"
        },
        on: {
          click: function($event) {
            _vm.sold(_vm.checkedWarehouses)
          }
        }
      },
      [_vm._v("賣出")]
    ),
    _vm._v(" "),
    _c(
      "table",
      {
        staticClass:
          "table table-striped table-bordered table-responsive table-h3"
      },
      [
        _c("thead", [
          _c("tr", [
            _c("td", [
              _c("input", {
                attrs: { type: "button", value: _vm.showCheckboxAllName },
                on: {
                  click: function($event) {
                    _vm.warehouseCheckboxAll()
                  }
                }
              })
            ]),
            _vm._v(" "),
            _c("td", [_vm._v("名稱")]),
            _vm._v(" "),
            _c("td", [_vm._v("買入金額")]),
            _vm._v(" "),
            _c("td", [_vm._v("買入日期")])
          ])
        ]),
        _vm._v(" "),
        _c(
          "tbody",
          _vm._l(_vm.warehouse.items, function(warehouse, id) {
            return _c(
              "tr",
              {
                class: _vm.checkedWarehouses[id] == undefined ? "" : "bg-danger"
              },
              [
                _c("td", [
                  _c("input", {
                    attrs: { type: "checkbox", name: "warehouseCheckbox[]" },
                    domProps: { value: id },
                    on: {
                      click: function($event) {
                        _vm.setWarehouses()
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c(
                  "td",
                  {
                    on: {
                      click: function($event) {
                        _vm.tdChecked(id)
                      }
                    }
                  },
                  [_vm._v(_vm._s(warehouse.name))]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  {
                    on: {
                      click: function($event) {
                        _vm.tdChecked(id)
                      }
                    }
                  },
                  [_vm._v(_vm._s(warehouse.buy_price))]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  {
                    on: {
                      click: function($event) {
                        _vm.tdChecked(id)
                      }
                    }
                  },
                  [_vm._v(_vm._s(warehouse.buy_date))]
                )
              ]
            )
          })
        )
      ]
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("td", [_vm._v("名稱")]),
        _vm._v(" "),
        _c("td", [_vm._v("目前金額")])
      ])
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-5f05bb42", module.exports)
  }
}

/***/ }),

/***/ 54:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(55)
/* template */
var __vue_template__ = __webpack_require__(56)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/js/components/WarehouseDealTable.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-25da6b94", Component.options)
  } else {
    hotAPI.reload("data-v-25da6b94", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 55:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['dealRecord'],
    data: function data() {
        return {};
    }
});

/***/ }),

/***/ 56:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "table",
      {
        staticClass:
          "table table-striped table-bordered table-responsive table-h3"
      },
      [
        _vm._m(0),
        _vm._v(" "),
        _c(
          "tbody",
          _vm._l(_vm.dealRecord, function(record, index) {
            return _c("tr", { class: record.loseMoney ? "bg-danger" : "" }, [
              _c("td", [_vm._v(_vm._s(record.name))]),
              _vm._v(" "),
              _c("td", [_vm._v(_vm._s(record.buy_price))]),
              _vm._v(" "),
              _c("td", [_vm._v(_vm._s(record.sold_price))]),
              _vm._v(" "),
              _c("td", [_vm._v(_vm._s(record.buy_date))]),
              _vm._v(" "),
              _c("td", [_vm._v(_vm._s(record.sold_date))])
            ])
          })
        )
      ]
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("td", [_vm._v("名稱")]),
        _vm._v(" "),
        _c("td", [_vm._v("買入金額")]),
        _vm._v(" "),
        _c("td", [_vm._v("賣出金額")]),
        _vm._v(" "),
        _c("td", [_vm._v("買入日期")]),
        _vm._v(" "),
        _c("td", [_vm._v("賣出日期")])
      ])
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-25da6b94", module.exports)
  }
}

/***/ }),

/***/ 57:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(58)
/* template */
var __vue_template__ = __webpack_require__(59)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/js/components/Pagination.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3895afde", Component.options)
  } else {
    hotAPI.reload("data-v-3895afde", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 58:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['pagination', 'getPage'],
    data: function data() {
        return {};
    }
});

/***/ }),

/***/ 59:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "ul",
      { staticClass: "pagination", class: _vm.pagination.hasPages },
      [
        _vm._l(_vm.pagination.lastPage, function(index) {
          return _c(
            "li",
            {
              class:
                index == _vm.pagination.currentPage ? "disabled" : "pointer"
            },
            [
              index != _vm.pagination.currentPage
                ? _c(
                    "a",
                    {
                      attrs: { rel: "prev" },
                      on: {
                        click: function($event) {
                          _vm.getPage(index)
                        }
                      }
                    },
                    [_vm._v(_vm._s(index))]
                  )
                : _vm._e(),
              _vm._v(" "),
              index == _vm.pagination.currentPage
                ? _c("a", { attrs: { rel: "prev" } }, [_vm._v(_vm._s(index))])
                : _vm._e()
            ]
          )
        }),
        _vm._v(" "),
        _c("li", { staticClass: "disabled" }, [
          _c("span", [
            _vm._v(
              "目前第 " +
                _vm._s(_vm.pagination.currentPage) +
                " 頁，總共 " +
                _vm._s(_vm.pagination.total ? _vm.pagination.total : 0) +
                " 筆數"
            )
          ])
        ])
      ],
      2
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-3895afde", module.exports)
  }
}

/***/ })

/******/ });