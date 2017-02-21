'use strict';

App.factory('Pdf', ['$rootScope', '$filter', 'lodash', function ($rootScope, $filter, lodash) {
  var pdf = {};

  function resolveFormGroup(columns) {
    var cols = [];

    angular.forEach(columns, function (col) {
      var children = [];
      angular.forEach(col, function (child) {
        if (child ? !child.hidden : false) {
          if (child.formGroups) {
            var resFormGroup = resolveFormGroup(child.formGroups);
            if (resFormGroup.length > 0) {
              children.push({
                columns: resolveFormGroup(child.formGroups)
              });
            }
          } else {
            var content = {};

            if (child.image) {
              content = {
                image: (child.image == 'N' ? 'noImage' : child.image),
                fit: [child.imageWidth || "*", child.imageHeight || 50]
              };
            } else if (child.checkbox) {
              content = {
                image: child.checkbox == "Y" ? 'check' : 'uncheck',
                fit: [child.size || 8, child.size || 8],
                width: child.width || 10,
                style: 'checkbox'
              };
            } else {
              var text = child.value;
              // if (child.type == "currency") {
              //   text = Currency.format(child.value || "");
              // }
              content = {
                text: ": " + text || "",
                width: "*",
                style: 'value'
              };
            }

            children.push({
              columns: [{
                text: child.label || "-",
                width: child.labelWidth || 100
              }, content],
              style: "formGroup"
            });
          }
        }
      });

      cols.push(children)
    });

    return cols;
  }

  function getColWidth(w) {
    return getColWidthNumeric(w) + "%";
  }

  function getColWidthNumeric(w) {
    var colWidth;
    switch (w) {
      case 12:
        colWidth = 100;
        break;
      case 11:
        colWidth = 91.66666667;
        break;
      case 10:
        colWidth = 83.33333333;
        break;
      case 9:
        colWidth = 75;
        break;
      case 8:
        colWidth = 66.66666667;
        break;
      case 7:
        colWidth = 58.33333333;
        break;
      case 6:
        colWidth = 50;
        break;
      case 5:
        colWidth = 41.66666667;
        break;
      case 4:
        colWidth = 33.33333333;
        break;
      case 3:
        colWidth = 25;
        break;
      case 2:
        colWidth = 16.66666667;
        break;
      case 1:
        colWidth = 8.33333333;
        break;
      default:
        colWidth = 100
    }
    return colWidth;
  }

  function getMargin(tType, tMargin) {
    var m = tMargin;

    var margin = [];
    switch (tType) {
      case 'a':
        margin = {
          margin: [m, m, m, m]
        };
        break;

      case 'l':
        margin = {
          marginLeft: 0
        };
        break;

      case 't':
        margin = {
          marginTop: m
        };
        break;

      case 'r':
        margin = {
          marginRight: m
        };
        break;

      case 'b':
        margin = {
          marginBottom: m
        };
        break;

      case 'y':
        margin = {
          margin: [0, m, 0, m]
        };
        break;

      case 'x':
        margin = {
          margin: [m, 0, m, 0]
        };
        break;

      default:
      // code
    }

    return margin;
  }

  function resolveColumn(columns) {
    var cols = [];
    angular.forEach(columns, function (col) {
      var children = [];
      var childrenContent = [];
      var totalWidth = 0;
      var colState = 0;
      angular.forEach(col, function (child) {
        var content = {};
        var colWidth = '100%';
        var alignment = 'left';
        var colClassSplit = child.colClass ? child.colClass.split(',') : ["p"];

        colState++;

        if (child.width) {
          colWidth = child.width;
          totalWidth = 0;
        } else if (child.colMd) {
          colWidth = getColWidth(child.colMd);
          totalWidth += child.colMd;
        } else {
          totalWidth = 12;
        }

        if (child.colGroups) {
          childrenContent.push({
            width: colWidth,
            columns: resolveColumn(child.colGroups),
            style: colClassSplit
          });
        } else if (child.formGroups) {
          children.push({
            width: colWidth,
            columns: resolveFormGroup(child.formGroups),
            style: colClassSplit
          });
        } else if (child.table) {
          children.push({
            width: colWidth,
            columns: [resolveTable(child.table)],
            style: colClassSplit
          });
        } else {
          var i = 0;
          angular.forEach(colClassSplit, function (item) {
            colClassSplit[i] = item.trim();
            i++;
          });

          if (child.align) {
            alignment = child.align;
          }
          if (child.image) {
            content = {
              image: (child.image == 'N' ? 'noImage' : child.image),
              fit: child.noFit ? null : [child.imageWidth || "*", child.imageHeight || 50]
            };

            if (child.noFit) {
              content.width = child.imageWidth || "100%";
            }
          } else if (child.checkbox != undefined) {
            content = {
              image: child.checkbox ? 'check' : 'uncheck',
              fit: [child.size || 8, child.size || 8],
              width: child.width || 10,
              style: 'checkbox'
            };
          } else if (child.pagebreak) {
            content = ({
              text: ' ',
              fontSize: 1,
              pageBreak: "after",
              margin: [0, 0, 0, 0]
            })
          } else if (child.break) {
            content = ({
              text: ' ',
              fontSize: 1,
              margin: [0, 10, 0, 10],
              width: "100%"
            })
          }
          else if (child.text != undefined) {
            content = {
              text: child.text || "",
              width: colWidth,
              alignment: alignment,
              style: colClassSplit
            };
          }

          childrenContent.push(content);
        }

        if (childrenContent.length > 0) {
          if (totalWidth >= 12) {
            children.push({
              columns: childrenContent
            });
            childrenContent = [];
            totalWidth = 0;
          } else if (colState == col.length) {
            children.push({
              columns: childrenContent
            });
          }
        }
      });

      cols.push(children)
    });

    return cols;
  }

  function resolveContent(content) {
    var result = [];
    angular.forEach(content, function (val) {
      result.push(resolveChildContent(val));
    });

    return result;
  }

  function resolveTable(table) {
    var body = [];

    // resolve fields
    var fields = [];
    var widths = [];

    var autoNumber = 0;

    if (table.showNumber) {

      autoNumber = lodash.findIndex(table.fields, function (o) {
        return o.id == 'no'
      });

      // resolve header
      if (autoNumber == -1) {
        fields.push({
          text: "",
          style: 'tableHeader'
        });
        widths.push(15);
      }
    }

    // resolve subtitle
    if (table.title) {
      var title = [];

      title.push({
        text: table.title || "",
        colSpan: table.fields.length + (table.showNumber && autoNumber > -1 ? 1 : 0),
        style: 'tableHeader'
      });

      angular.forEach(_.range(table.fields.length - 1), function () {
        title.push({text: ""})
      });

      body.push(title);
    }

    // resolve title
    if (table.subTitles) {
      angular.forEach(table.subTitles, function (subTitle) {
        var subTitles = [];
        subTitles.push({
          text: subTitle || "",
          colSpan: table.fields.length + (table.showNumber && autoNumber > -1 ? 1 : 0),
          style: 'tableHeader'
        });

        angular.forEach(_.range(table.fields.length - 1), function () {
          subTitles.push({text: ""})
        });

        body.push(subTitles);
      });
    }

    angular.forEach(table.fields, function (field) {
      fields.push({
        text: field.title || "Undefined",
        style: 'tableHeader'
      });

      if (field.id == 'no') widths.push(15);
      else widths.push(field.xwidth || field.width || "*")
    });

    body.push(fields);

    // resolve row
    angular.forEach(table.data, function (row, key) {
      var data = [];
      if (table.showNumber && autoNumber == -1) {
        data.push({
          text: (key + 1).toString(),
          style: 'tableBody'
        });
      }

      angular.forEach(table.fields, function (field) {
        var text = "";
        var image = "";
        var value;

        if (row[field.id] && row[field.id].text) {
          value = row[field.id].text
        } else {
          value = row[field.id]
        }

        if (field.type == "currency") {
          // text = Currency.format((value || "").toString());
        } else if (field.type == "date") {
          text = $filter('date')(value, 'medium');
        } else if (field.type == "checkbox") {
          image = value == "Y" ? 'check' : 'uncheck';
        } else if (field.type == "native") {
          text = field.id;
        }
        else if (field.type == "defaultValue") {
          text = (value) == null ? field.default : (value);
        }
        else {
          if (angular.isArray(field.id)) {
            angular.forEach(field.id, function (val, key) {
              if (key > 0) {
                text += field.concat || " ";
              }
              text += ((row[val]) || "").toString();
            })
          } else {
            text = (value || "").toString();
          }
        }

        if (image != "") {
          data.push({
            image: image,
            fit: [field.size || 8, field.size || 8],
            width: field.width || 10,
            style: 'tableBody checkbox'
          });
        } else {
          data.push({
            text: text || "",
            style: row[field.id] && row[field.id].style ? row[field.id].style : 'tableBody',
            colSpan: row[field.id] && row[field.id].colSpan ? row[field.id].colSpan : 0
          });
        }
      });

      body.push(data);
    });

    var isFooterExist = lodash.filter(table.fields, function (f) {
        return f.footer != undefined;
      }).length > 0;

    // resolve footer
    if (isFooterExist) {
      var footers = [];

      if (table.showNumber && autoNumber == -1) {
        footers.push({
          text: "", style: 'tableHeader'
        });
      }

      angular.forEach(table.fields, function (field) {
        footers.push({
          text: field.footer || "",
          style: 'tableHeader'
        });
      });

      body.push(footers);
    }

    return {
      table: {
        headerRows: 1,
        keepWithHeaderRows: 1,
        widths: widths,
        body: body,
        style: 'table'
      },
      layout: 'noBorders'
    };
  }

  function resolveChildContent(item) {
    var result = [];

    angular.forEach(item, function (val, key) {
      var resultObj = {};

      if (key.toLowerCase() == 'shownotifmessage') {
        var resultNotifObj = {
          style: ['h6', 'mb10']
        };
        if ($rootScope.httpResultNotif.successMessage || $rootScope.httpResultNotif.errorMessage) {
          if ($rootScope.httpResultNotif.successMessage) {
            resultNotifObj.text = $rootScope.messageResource($rootScope.httpResultNotif.successMessage);
            resultNotifObj.style.push('success');
          } else if ($rootScope.httpResultNotif.errorMessage) {
            resultNotifObj.text = $rootScope.messageResource($rootScope.httpResultNotif.errorMessage);
            resultNotifObj.style.push('error');
          }

          result.push(resultNotifObj)
        }
      }

      if (key.toLowerCase() == 'title') {
        resultObj = {
          text: val || "",
          style: 'header'
        };
        result.push(resultObj)
      } else if (key.toLowerCase() == 'subtitle') {
        resultObj = {
          text: val || "",
          style: 'subHeader'
        };
        result.push(resultObj);
      } else if (key.toLowerCase() == 'text') {
        result.push({
          text: val || ""
        });

      } else if (key.toLowerCase() == 'formgroups') {
        resultObj = {
          columns: resolveFormGroup(val),
          style: 'container'
        };
        result.push(resultObj)
      } else if (key.toLowerCase() == 'colgroups') {
        resultObj = resolveColumn(val);
        result.push(resultObj)
      } else if (key.toLowerCase() == 'table') {
        result.push(resolveTable(val));
      } else if (key.toLowerCase() == 'pagebreak') {
        result.push({
          text: ' ',
          fontSize: 1,
          pageBreak: val || "after",
          margin: [0, 0, 0, 0]
        })
      } else if (key.toLowerCase() == 'break') {
        result.push({
          text: ' ',
          fontSize: 1,
          margin: [0, 10, 0, 10],
          width: "100%"
        })
      }
    });

    return result;
  }

  function getContentById(id) {
    function ParseContainer(cnt, e, p, styles) {
      var elements = [];
      var children = e.childNodes;
      if (children.length != 0) {
        for (var i = 0; i < children.length; i++) p = ParseElement(elements, children[i], p, styles);
      }
      if (elements.length != 0) {
        for (var i = 0; i < elements.length; i++) cnt.push(elements[i]);
      }
      return p;
    }

    function ComputeStyle(o, styles) {
      for (var i = 0; i < styles.length; i++) {
        var st = styles[i].trim().toLowerCase().split(":");
        if (st.length == 2) {
          switch (st[0]) {
            case "font-size": {
              o.fontSize = parseInt(st[1]);
              break;
            }
            case "text-align": {
              switch (st[1]) {
                case "right":
                  o.alignment = 'right';
                  break;
                case "center":
                  o.alignment = 'center';
                  break;
              }
              break;
            }
            case "font-weight": {
              switch (st[1]) {
                case "bold":
                  o.bold = true;
                  break;
              }
              break;
            }
            case "text-decoration": {
              switch (st[1]) {
                case "underline":
                  o.decoration = "underline";
                  break;
              }
              break;
            }
            case "font-style": {
              switch (st[1]) {
                case "italic":
                  o.italics = true;
                  break;
              }
              break;
            }
          }
        }
      }
    }

    function ParseElement(cnt, e, p, styles) {
      if (!styles) styles = [];
      if (e.getAttribute) {
        var nodeStyle = e.getAttribute("style");
        if (nodeStyle) {
          var ns = nodeStyle.split(";");
          for (var k = 0; k < ns.length; k++) styles.push(ns[k]);
        }
      }

      switch (e.nodeName.toLowerCase()) {
        case "#text": {
          var t = {
            text: e.textContent.replace(/\n/g, "")
          };
          if (styles) ComputeStyle(t, styles);
          p.text.push(t);
          break;
        }
        case "b":
        case "strong": {
          //styles.push("font-weight:bold");
          ParseContainer(cnt, e, p, styles.concat(["font-weight:bold"]));
          break;
        }
        case "u": {
          //styles.push("text-decoration:underline");
          ParseContainer(cnt, e, p, styles.concat(["text-decoration:underline"]));
          break;
        }
        case "i": {
          //styles.push("font-style:italic");
          ParseContainer(cnt, e, p, styles.concat(["font-style:italic"]));
          //styles.pop();
          break;
          //cnt.push({ text: e.innerText, bold: false });
        }
        case "span": {
          ParseContainer(cnt, e, p, styles);
          break;
        }
        case "br": {
          p = CreateParagraph();
          cnt.push(p);
          break;
        }
        case "table": {
          var t = {
            table: {
              widths: [],
              body: []
            }
          }
          var border = e.getAttribute("border");
          var isBorder = false;
          if (border)
            if (parseInt(border) == 1) isBorder = true;
          if (!isBorder) t.layout = 'noBorders';
          ParseContainer(t.table.body, e, p, styles);

          var widths = e.getAttribute("widths");
          if (!widths) {
            if (t.table.body.length != 0) {
              if (t.table.body[0].length != 0)
                for (var k = 0; k < t.table.body[0].length; k++) t.table.widths.push("*");
            }
          } else {
            var w = widths.split(",");
            for (var k = 0; k < w.length; k++) t.table.widths.push(w[k]);
          }
          cnt.push(t);
          break;
        }
        case "tbody": {
          ParseContainer(cnt, e, p, styles);
          //p = CreateParagraph();
          break;
        }
        case "tr": {
          var row = [];
          ParseContainer(row, e, p, styles);
          cnt.push(row);
          break;
        }
        case "td": {
          p = CreateParagraph();
          var st = {
            stack: []
          };

          st.stack.push(p);

          var rspan = e.getAttribute("rowspan");
          if (rspan) st.rowSpan = parseInt(rspan);
          var cspan = e.getAttribute("colspan");
          if (cspan) st.colSpan = parseInt(cspan);

          ParseContainer(st.stack, e, p, styles);
          cnt.push(st);
          break;
        }
        case "div":
        case "p": {
          p = CreateParagraph();
          var st = {
            stack: []
          }
          st.stack.push(p);
          ComputeStyle(st, styles);
          ParseContainer(st.stack, e, p);

          cnt.push(st);
          break;
        }
        case "h1": {
          ParseContainer(cnt, e, p, styles.concat(["font-size:32px"]));
          break;
        }
        case "h2": {
          ParseContainer(cnt, e, p, styles.concat(["font-size:24px"]));
          break;
        }
        case "h3": {
          ParseContainer(cnt, e, p, styles.concat(["font-size:19px"]));
          break;
        }
        case "h4": {
          ParseContainer(cnt, e, p, styles.concat(["font-size:16px"]));
          break;
        }
        case "h5": {
          ParseContainer(cnt, e, p, styles.concat(["font-size:14px"]));
          break;
        }
        case "h6": {
          ParseContainer(cnt, e, p, styles.concat(["font-size:13px"]));
          break;
        }
        case "img": {
          var maxResolution = {
            width: 570,
            height: 830
          };

          var width = parseInt(e.getAttribute("width")) || 570;
          var height = parseInt(e.getAttribute("height"));

          if (width > maxResolution.width) {
            var scaleByWidth = maxResolution.width / width;
            width = (width * scaleByWidth) - 50;
            height = (height * scaleByWidth) - 50;
          }
          if (height > maxResolution.height) {
            var scaleByHeight = maxResolution.height / height;
            width = (width * scaleByHeight) - 50;
            height *= (height * scaleByHeight) - 50;
          }

          var myImage = {
            image: e.getAttribute("src"),
            width: width,
            height: height
          };

          cnt.push(myImage);
          break;
        }
        default: {
          console.log("Parsing for node " + e.nodeName + " not found");
          break;
        }
      }
      return p;
    }

    function ParseHtml(cnt, htmlText) {
      var html = $(htmlText.replace(/\t/g, "").replace(/\n/g, ""));
      var p = CreateParagraph();
      for (var i = 0; i < html.length; i++) ParseElement(cnt, html.get(i), p);
    }

    function CreateParagraph() {
      var p = {
        text: []
      };
      return p;
    }

    var content = [];
    ParseHtml(content, document.getElementById(id).outerHTML);
    return content;
  }

  pdf.generateDocDefinition = function (content, filename, orientation, pageSize) {
    return {
      info: {
        title: filename || 'Document'
      },
      pageOrientation: orientation || 'portrait',
      pageSize: pageSize || 'A4',
      header: {
        text: $filter('date')(new Date(), 'medium'),
        style: 'footer',
        alignment: 'right'
      },
      content: resolveContent(content),
      footer: function (currentPage, pageCount) {
        return {
          text: "Page " + currentPage.toString() + ' of ' + pageCount,
          style: 'footer'
        };
      },
      pageMargins: [20, 35, 20, 40],
      styles: {
        header: {
          fontSize: 16,
          bold: true,
          margin: [0, 0, 0, 0]
        },
        subHeader: {
          fontSize: 10,
          bold: false,
          margin: [0, 5, 0, 5]
        },
        table: {
          fontSize: 8,
          margin: [5, 0, 5, 5]
        },
        tableHeader: {
          bold: true,
          fontSize: 9,
          color: '#000000',
          fillColor: '#ebebeb',
          margin: [3, 2, 3, 2]
        },
        tableSubHeader: {
          fontSize: 9,
          color: '#000000',
          fillColor: '#ebebeb',
          margin: [3, 2, 3, 2]
        },
        tableBody: {
          fontSize: 9,
          margin: [3, 2, 3, 2]
        },
        footer: {
          margin: [20, 20, 20, 20],
          fontSize: 8,
          alignment: "right"
        },
        container: {
          fontSize: 9,
          margin: [0, 5, 0, 10]
        },
        formGroup: {
          fontSize: 9,
          margin: [0, 3, 0, 3]
        },
        value: {
          bold: true
        },
        h1: {
          fontSize: 32
        },
        h2: {
          fontSize: 24
        },
        h3: {
          fontSize: 19
        },
        h4: {
          fontSize: 16
        },
        h5: {
          fontSize: 14
        },
        h6: {
          fontSize: 13
        },
        p: {
          fontSize: 9,
          margin: [0, 4, 0, 0]
        },
        span: {
          fontSize: 9
        },
        bold: {
          bold: true
        },
        left: {
          alignment: 'left'
        },
        right: {
          alignment: 'right'
        },
        success: {
          color: "#5DAF50"
        },
        error: {
          color: "#F44A36"
        },
        colBreak: {
          margin: [0, 0, 0, 5]
        },
        checkbox: {
          margin: [0, 2, 3, 0]
        },
        checkboxLabel: {
          margin: [0, 2, 0, 0]
        },
        ma0: getMargin('a', 0),
        ma5: getMargin('a', 5),
        ma10: getMargin('a', 10),
        ma15: getMargin('a', 15),
        ma20: getMargin('a', 20),
        ma25: getMargin('a', 25),
        ma30: getMargin('a', 30),
        ma35: getMargin('a', 35),
        ma40: getMargin('a', 40),
        ma45: getMargin('a', 45),
        ma50: getMargin('a', 50),

        mt0: getMargin('t', 0),
        mt5: getMargin('t', 5),
        mt10: getMargin('t', 10),
        mt15: getMargin('t', 15),
        mt20: getMargin('t', 20),
        mt25: getMargin('t', 25),
        mt30: getMargin('t', 30),
        mt35: getMargin('t', 35),
        mt40: getMargin('t', 40),
        mt45: getMargin('t', 45),
        mt50: getMargin('t', 50),

        mr0: getMargin('r', 0),
        mr5: getMargin('r', 5),
        mr10: getMargin('r', 10),
        mr15: getMargin('r', 15),
        mr20: getMargin('r', 20),
        mr25: getMargin('r', 25),
        mr30: getMargin('r', 30),
        mr35: getMargin('r', 35),
        mr40: getMargin('r', 40),
        mr45: getMargin('r', 45),
        mr50: getMargin('r', 50),

        mb0: getMargin('b', 0),
        mb5: getMargin('b', 5),
        mb10: getMargin('b', 10),
        mb15: getMargin('b', 15),
        mb20: getMargin('b', 20),
        mb25: getMargin('b', 25),
        mb30: getMargin('b', 30),
        mb35: getMargin('b', 35),
        mb40: getMargin('b', 40),
        mb45: getMargin('b', 45),
        mb50: getMargin('b', 50),

        ml0: getMargin('l', 0),
        ml5: getMargin('l', 5),
        ml10: getMargin('l', 10),
        ml15: getMargin('l', 15),
        ml20: getMargin('l', 20),
        ml25: getMargin('l', 25),
        ml30: getMargin('l', 30),
        ml35: getMargin('l', 35),
        ml40: getMargin('l', 40),
        ml45: getMargin('l', 45),
        ml50: getMargin('l', 50),

        mx0: getMargin('x', 0),
        mx5: getMargin('x', 5),
        mx10: getMargin('x', 10),
        mx15: getMargin('x', 15),
        mx20: getMargin('x', 20),
        mx25: getMargin('x', 25),
        mx30: getMargin('x', 30),
        mx35: getMargin('x', 35),
        mx40: getMargin('x', 40),
        mx45: getMargin('x', 45),
        mx50: getMargin('x', 50),

        my0: getMargin('y', 0),
        my5: getMargin('y', 5),
        my10: getMargin('y', 10),
        my15: getMargin('y', 15),
        my20: getMargin('y', 20),
        my25: getMargin('y', 25),
        my30: getMargin('y', 30),
        my35: getMargin('y', 35),
        my40: getMargin('y', 40),
        my45: getMargin('y', 45),
        my50: getMargin('y', 50)
      },
      defaultStyle: {
        columnGap: 5
      },
      images: {
        check: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QBYRXhpZgAATU0AKgAAAAgABAExAAIAAAARAAAAPlEQAAEAAAABAQAAAFERAAQAAAABAAAAAFESAAQAAAABAAAAAAAAAABBZG9iZSBJbWFnZVJlYWR5AAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAAaABoDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD6q/4Jpf8ABNL4cf8ABTT9nG1/aC/aBtdZ+JPjb4k6jqNyBc65e2lrpNvDezWyQQpbyRlUUwMQpJUKwAAxX0N/xD6fshf9Ej/8unWv/kyvnP8AYJ/4KbeAf+Cav/BFj9n3U/GlrrWp3Piu/wBatLGx0yAPK0Uet3ZuJyzFUCxLIh2ltzs6gDG5l9C/4LCf8FmPDHwP/Ys0ib4T+JrPWvFnxf09z4f1CxmydLsSSk16Rw0cqndEisAyyhyRmFloA8A0n9mX9hL4/ftn+J/2dvhv4J8X+E/iHpcdzFovjPSde1G5s01G2gklmVTLcyBRCyEb2j2u0bKrDKF/kzwh/wAHEn7S3hDwnpekp4l0y+TS7SK0W5vbBJ7m4EaBN8sjcu7YyzHkkknrX6af8G+3/BMRv2P/AIFt8S/GNg0XxI+IlokiwzriXRNNYiSO3IPKyynbJIDyMRoQCjZ/njoA/ff/AIJT/s+fC79vz/gi14e+C3jZbW81Xwreata6hbI6R6t4avjqV1NFPGDlo3CXCckbWDMjBlLKfGP2Bf8Ag2+8WfCz9uZtY+K76LrXwz8DXIvtGkhmSQeKZg5MCyQZLQxocPKknDMAg8xWZh8r/wDBxJ4R0nwh/wAFLPEiaTpenaWl9Z217craWyQC4nkTdJK+0Dc7MSWY5JJySa+F6AP7EPip8VNA+Cfw+1bxT4o1Sz0fQ9EtpLq6urmZYkREUsQCxALEDAGck8V/I5pvwF8b6xp1veWnhLxFc2t3Gs0M0dhKySowBVlIGCCCCCPWj4Cabbax8bfCVpeW8F3a3OrW0c0MyCSOVDKoKsp4II4INf13eFfCul+HfC+m6fp+m2FhYWFrFb21tb26RQ28SIFSNEUAKqqAAAAAABQB/9k=",
        uncheck: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QB4RXhpZgAATU0AKgAAAAgABgExAAIAAAARAAAAVgMBAAUAAAABAAAAaAMDAAEAAAABAAAAAFEQAAEAAAABAQAAAFERAAQAAAABAAAOxFESAAQAAAABAAAOxAAAAABBZG9iZSBJbWFnZVJlYWR5AAAAAYagAACxj//bAEMAAgEBAgEBAgICAgICAgIDBQMDAwMDBgQEAwUHBgcHBwYHBwgJCwkICAoIBwcKDQoKCwwMDAwHCQ4PDQwOCwwMDP/bAEMBAgICAwMDBgMDBgwIBwgMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDP/AABEIABoAGgMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/APqr/gml/wAE0vhx/wAFNP2cbX9oL9oG11n4k+NviTqOo3IFzrl7aWuk28N7NbJBClvJGVRTAxCklQrAADFfQ3/EPp+yF/0SP/y6da/+TKP+DfT/AJRDfCP/ALjP/p6v6+zKAPhn4hf8G7/7MmseEL6Hwn4O1TwR4lETNput6f4k1OSfT7gKfLkCzTyIQr7Tjbnjgivyd8If8HEn7S3hDwnpekp4l0y+TS7SK0W5vbBJ7m4EaBN8sjcu7YyzHkkknrX9JFfxn0Af0lf8G8PxD0bV/wDgmV4M8JQ6hbf8JN4JvNWsNb0tpAt3p8ranczgSR53KCk8ZyQBkkdq+5q/m3/4OJPCOk+EP+ClniRNJ0vTtLS+s7a9uVtLZIBcTyJuklfaBudmJLMckk5JNfC9AH9iHxU+KmgfBP4fat4p8UapZ6PoeiW0l1dXVzMsSIiKWIBYgFiBgDOSeK/kc034C+N9Y063vLTwl4iubW7jWaGaOwlZJUYAqykDBBBBBHrR8BNNttY+NvhK0vLeC7tbnVraOaGZBJHKhlUFWU8EEcEGv67vCvhXS/DvhfTdP0/TbCwsLC1it7a2t7dIobeJECpGiKAFVVAAAAAAAoA//9k=",
        noImage: "data:image/png;base64,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"
      }
    };
  };

  pdf.generateDocDefinitionByElementId = function (id, filename, subTitle, orientation) {
    return {
      info: {
        title: filename || 'Document'
      },
      pageOrientation: orientation || 'portrait',
      header: {
        text: $filter('date')(new Date(), 'medium'),
        style: 'footer',
        alignment: 'right'
      },
      content: [
        { text: filename, style: 'header' },
        { text: subTitle, style: 'subTitle' },
        getContentById(id)
      ],
      footer: function (currentPage, pageCount) {
        return {
          text: "Page " + currentPage.toString() + ' of ' + pageCount,
          style: 'footer'
        };
      },
      pageMargins: [20, 35, 20, 40],
      defaultStyle: {
        columnGap: 5
      },
      styles: {
        footer: {
          margin: [20, 20, 20, 20],
          fontSize: 8,
          alignment: "right"
        },
        header: {
          fontSize: 22,
          bold: true
        },
        subTitle: {
          fontSize: 12
        }
      }
    };
  };

  return pdf;
}]);
