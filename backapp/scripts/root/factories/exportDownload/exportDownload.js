'use strict';

App.factory('ExportDownload', ['$rootScope', '$filter', 'Pdf', 'Converter', '$q',
  function ($rootScope, $filter, Pdf, Converter, $q) {
    function detectIE() {
      var ua = window.navigator.userAgent;

      var msie = ua.indexOf('MSIE ');
      if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
      }

      var trident = ua.indexOf('Trident/');
      if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
      }

      var edge = ua.indexOf('Edge/');
      if (edge > 0) {
        // Edge (IE 12+) => return version number
        return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
      }

      // other browser
      return false;
    }

    return {
      excel: function (content, filename) {
        var wbout = Excel.generateDocDefinition(content, filename);
        saveAs(new Blob(wbout, {type: "application/octet-stream"}), filename + ".xlsx")
      },
      excelByteArray: function (content, filename) {
        var wbout = Excel.generateDocDefinition(content, filename);
        //return new Blob(wbout, {type: "application/octet-stream"});
        var bytes = new Uint8Array( wbout[0] );
        var binary = '';
        var len = bytes.byteLength;
        for (var i = 0; i < len; i++) {
          binary += String.fromCharCode( bytes[ i ] );
        }
        var base64 = window.btoa( binary );
        return Converter.base64ToByteArray(base64);
      },
      pdf: function (content, filename, orientation, pageSize) {
        var docDefinition = Pdf.generateDocDefinition(content, filename, orientation, pageSize);
        pdfMake.createPdf(docDefinition).download(filename + '.pdf');
      },
      pdfByteArray: function (content, filename, orientation, pageSize) {
        var docDefinition = Pdf.generateDocDefinition(content, filename, orientation, pageSize);
        var deferred = $q.defer();

        pdfMake.createPdf(docDefinition).getBase64(function(encodedString) {
          deferred.resolve(Converter.base64ToByteArray(encodedString));
        });

        return deferred.promise;
      },
      pdfByteBase64: function (content, filename, orientation, pageSize) {
        var docDefinition = Pdf.generateDocDefinition(content, filename, orientation, pageSize);
        var deferred = $q.defer();

        pdfMake.createPdf(docDefinition).getBase64(function(encodedString) {
          deferred.resolve(encodedString);
        });

        return deferred.promise;
      },
      print: function (content, filename, orientation, pageSize) {
        if (detectIE()) {
          window.print();
        }
        else {
          var docDefinition = Pdf.generateDocDefinition(content, filename, orientation, pageSize);
          pdfMake.createPdf(docDefinition).print();
        }
      },
      downloadByElementId: function (id, filename, subTitle, orientation) {
        var docDefinition = Pdf.generateDocDefinitionByElementId(id, filename, subTitle, orientation);
        pdfMake.createPdf(docDefinition).download(filename + '.pdf');
      },
      printByElementId: function (id, filename, subTitle, orientation) {
        if (detectIE()) {
          window.print();
        }
        else {
          var docDefinition = Pdf.generateDocDefinitionByElementId(id, filename, subTitle, orientation);
          pdfMake.createPdf(docDefinition).print();
        }
      },

      txtData: function (content) {
        var dataTxt= '';
        angular.forEach(content, function (tableData) {
          angular.forEach(tableData.table.fields, function (field, idx) {
            if(idx == tableData.table.fields.length -1)
              dataTxt = dataTxt + field.title + '\n';
            else
              dataTxt = dataTxt + field.title + ',';
          });

          if(tableData.table.data.length > 0){

            angular.forEach(tableData.table.data, function (data) {
              angular.forEach(tableData.table.fields, function (field, idx) {
                if(idx == tableData.table.fields.length -1)
                  dataTxt += (data[field.id]||"").toString().replace(',','')  + '\n';
                else
                  dataTxt += (data[field.id]||"").toString().replace(',','') + ',';
              });
            });

          }

        });

        return dataTxt;
      }
    };
  }
]);
