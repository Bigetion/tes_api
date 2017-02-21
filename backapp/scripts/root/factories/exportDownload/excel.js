'use strict';

App.factory('Excel', ['$rootScope', '$filter', 'Currency', 'lodash', function ($rootScope, $filter, Currency, lodash) {
  var excel = {};

  var style = {
    border: {
      "top": {
        "style": "hair",
        "color": {
          "rgb": "DDDDDDDD"
        }
      },
      "left": {
        "style": "hair",
        "color": {
          "rgb": "DDDDDDDD"
        }
      },
      "right": {
        "style": "hair",
        "color": {
          "rgb": "DDDDDDDD"
        }
      },
      "bottom": {
        "style": "hair",
        "color": {
          "rgb": "DDDDDDDD"
        }
      }
    }
  };

  function datenum(v, date1904) {
    if (date1904) v += 1462;
    var epoch = Date.parse(v);
    return (epoch - new Date(Date.UTC(1899, 11, 30))) / (24 * 60 * 60 * 1000);
  }

  function spartaaaaanHuriaaa(data) {
    var ws = {};
    var range = {s: {c: 10000000, r: 10000000}, e: {c: 0, r: 0}};
    for (var R = 0; R != data.length; ++R) {
      for (var C = 0; C != data[R].length; ++C) {
        if (range.s.r > R) range.s.r = R;
        if (range.s.c > C) range.s.c = C;
        if (range.e.r < R) range.e.r = R;
        if (range.e.c < C) range.e.c = C;

        var row = (data[R][C]);

        var cell = {v: row.value};
        if (cell.v == null) continue;
        var cell_ref = XLSX.utils.encode_cell({c: C, r: R});

        if (typeof cell.v === 'number') cell.t = 'n';
        else if (typeof cell.v === 'boolean') cell.t = 'b';
        else if (cell.v instanceof Date) {
          cell.t = 'n';
          cell.z = XLSX.SSF._table[14];
          cell.v = datenum(cell.v);
        }
        else cell.t = 's';

        if (row.style) {
          cell.s = row.style;
        }

        ws[cell_ref] = cell;
      }
    }

    if (range.s.c < 10000000) ws['!ref'] = XLSX.utils.encode_range(range);
    return ws;
  }

  function Workbook() {
    if (!(this instanceof Workbook)) return new Workbook();
    this.SheetNames = [];
    this.Sheets = {};
  }

  function s2ab(s) {
    var buf = new ArrayBuffer(s.length);
    var view = new Uint8Array(buf);
    for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
    return buf;
  }

  function resolveTable(table) {
    var result = [];

    // resolve subtitle
    if (table.title) {
      result.push([$rootScope.lang[table.title] || table.title || ""]);
    }

    // resolve title
    if (table.subTitles) {
      angular.forEach(table.subTitles, function (subTitle) {
        result.push([$rootScope.lang[subTitle] || subTitle || ""]);
      });
    }

    var fields = [];

    var headerStyle = {
      "font": {
        "color": {
          "rgb": "000000"
        }
      },
      "fill": {
        "fgColor": {
          "rgb": "ebebeb"
        }
      },
      "border": style.border
    };

    angular.forEach(table.fields, function (field) {
      fields.push({value: $rootScope.lang[field.title] || field.title || "Undefined", style: headerStyle});
    });

    result.push(fields);

    angular.forEach(table.data, function (row) {
      var tableRow = [];
      angular.forEach(table.fields, function (field) {
        var text;

        if (field.type == "currency") {
          text = Currency.format((row[field.id] || "").toString());
        } else if (field.type == "date") {
          text = $filter('date')(row[field.id], 'medium');
        } else {
          text = row[field.id];
        }
        tableRow.push({
          value: text || "-", style: {
            "alignment": {
              "wrapText": 1,
              "horizontal": "left",
              "vertical": "center"
            },
            "border": style.border
          }
        })
      });

      result.push(tableRow);
    });

    var isFooterExist = lodash.filter(table.fields, function (f) {
        return f.footer != undefined;
      }).length > 0;

    // resolve footer
    if (isFooterExist) {
      var footers = [];
      angular.forEach(table.fields, function (field) {
        footers.push({value: $rootScope.lang[field.footer] || field.footer || "", style: headerStyle});
      });

      result.push(footers);
    }

    return result;
  }

  function resolveFormGroup(columns) {
    var cols = [];

    angular.forEach(columns, function (col) {
      angular.forEach(col, function (child) {
        if (!child.hidden) {
          cols.push([
            {value: child.label}, {value: ":"}, {value: child.value}
          ])
        }
      });
    });

    return cols;
  }

  function resolveColGroup(columns) {
    var cols = [];

    angular.forEach(columns, function (col) {
      angular.forEach(col[0].formGroups, function (childs) {
        angular.forEach(childs, function (child) {
          if (!child.hidden) {
            var text = child.value;
            if (child.type == "currency") {
              text = Currency.format((child.value || "").toString());
            } else if (child.type == "date") {
              text = $filter('date')(child.value, 'medium');
            } else {
              text = child.value;
            }
            cols.push([
              {value: child.label}, {value: ":"}, {value: text}
            ])
          }
        });
      });
    });

    return cols;
  }


  function resolveContent(content) {
    var result = [];

    angular.forEach(content, function (val, key) {
      result = result.concat(resolveChildContent(val));
    });

    return result;
  }

  function resolveChildContent(item) {
    var result = [];

    angular.forEach(item, function (val, key) {
      if (key.toLowerCase() == 'title') {
        result.push([{
          value: val,
          style: {
            "font": {
              "sz": 24
            }
          }
        }])
      } else if (key.toLowerCase() == 'subtitle') {
        result.push([{
          value: val,
          style: {
            "font": {
              "sz": 18
            }
          }
        }]);
      } else if (key.toLowerCase() == 'formgroups') {
        result = result.concat(resolveFormGroup(val));
      } else if (key.toLowerCase() == 'colgroups') {
        result = result.concat(resolveColGroup(val));
      } else if (key.toLowerCase() == 'table') {
        result = result.concat(resolveTable(val));
      } else if (key.toLowerCase() == 'break') {
        result.push([]);
        result.push([]);
      }
    });

    return result;
  }


  excel.generateDocDefinition = function (content, filename) {
    /* original data */
    var data = resolveContent(content);
    var ws_name = filename;

    var wb = new Workbook(), ws = spartaaaaanHuriaaa(data);

    /* add worksheet to workbook */
    wb.SheetNames.push(ws_name);
    wb.Sheets[ws_name] = ws;

    return [s2ab(XLSX.write(wb, {bookType: 'xlsx', bookSST: true, type: 'binary'}))];
  };

  return excel;
}])
;
