var cols, rows, isDraggedBetweenCells = !1,
    isMouseDown = !1,
    mouseDownCell, selectedRowspan, selectedColspan;

function isInt(a) {
    return /^\d+$/.test(a)
}
Array.indexOf || (Array.prototype.indexOf = function (a) {
    for (var e = 0; e < this.length; e++) if (this[e] == a) return e;
    return -1
});

function RemoveSelection() {
    window.getSelection ? window.getSelection().removeAllRanges() : document.selection.createRange && (document.selection.createRange(), document.selection.empty())
}
function getCellRows(a) {
    return getCellValue(a, "r")
}

function getCellOffset(a) {
    return getCellValue(a, "o")
}
function getCellCols(a) {
    return getCellValue(a, "c")
}
function getCellValue(a, e) {
    var b = jQuery(a).attr("class"),
        b = b.split(" ");
    allClassesLength = b.length;
    for (var c = 0; c < allClassesLength; c++) b[c].charAt(0) === e ? b[c] = parseInt(b[c].substr(1, b[c].length - 1), 10) : (b.splice(c, 1), allClassesLength--, c--);
    return b
}

function exportHTML() {
    var a = jQuery("tbody").clone(),
        a = a.html(),
        a = a.toLowerCase(),
        a = a.replace(/rowspan="1"/gi, ""),
        a = a.replace(/colspan="1"/gi, ""),
        a = a.replace(/class="([^"]*)"/gi, ""),
        a = a.replace(/class="([^"]*)"/gi, ""),
        a = a.replace(/<div contenteditable="true">/gi, ""),
        a = a.replace(/<div contenteditable=true>/gi, ""),
        a = a.replace(/&nbsp;/gi, ""),
        a = a.replace(/<\/div>/gi, ""),
        a = a.replace(/\s\s\s/gi, " "),
        a = a.replace(/\s\s/gi, " "),
        a = a.replace(/<td\s>/gi, "<td>"),
        a = a.replace(/<tr>\s<td/gi, "<tr><td"),
        a = a.replace(/> <\/td>/gi,
            "></td>"),
        a = a.replace(/<\/td>\s</gi, "</td><"),
        a = a.replace(/<tr>/gi, "\n    <tr>\n"),
        a = a.replace(/<\/tr>/gi, "\n    </tr>"),
        a = a.replace(/d><td/gi, "d>\n<td"),
        a = a.replace(/<td/gi, "        <td"),
        a = a.replace(/<td>\s/gi, "<td>"),
        a = a.replace(/\s>\s/gi, ">"),
        a = a.replace(/" >/gi, '">');
    jQuery("#export").val("<table>" + a + "\n</table>")
}

function reindexTable() {
    for (var a = [], e = [], b = 0; b < cols; b++) a[b] = "c" + b;
    for (b = 0; b < rows; b++) e[b] = a.slice();
    for (var a = jQuery("tr"), c, g, f, i, b = 0; b < rows; b++) {
        c = a.eq(b).children();
        g = c.size();
        for (var j = colOffset = 0; j < g; j++) {
            c.eq(j).removeClass();
            f = parseInt(c.eq(j).attr("colspan"), 10);
            i = parseInt(c.eq(j).attr("rowspan"), 10);
            void 0 == c.eq(j).attr("colspan") && (f = 1);
            void 0 == c.eq(j).attr("rowspan") && (i = 1);
            for (var h = 0; h < i; h++) for (var o = 0; o < f; o++) {
                for (var k = 0;
                "" === e[b + h][o + colOffset + k];) k++;
                c.eq(j).addClass(e[b + h][o + colOffset + k] + " r" + (b + h));
                e[b + h].splice(o + k + colOffset, 1, "")
            }
            colOffset += f
        }
    }
}
jQuery(function () {
    jQuery("#generate").on("click", function () {
        cols = parseInt(jQuery("#cols").val(), 10);
        rows = parseInt(jQuery("#rows").val(), 10);
        if (isInt(cols)) if (isInt(rows)) {
            jQuery("#tableWrap").empty().append("<table>");
            for (var a = 1; a <= rows; a++) {
                jQuery("table").append("<tr></tr>");
                $generatedRow = jQuery("tr").eq(a - 1);
                for (var e = 1; e <= cols; e++) $generatedRow.append("<td class='c" + (e - 1) + " r" + (a - 1) + "' colspan='1' rowspan='1'><div contenteditable='true'>&nbsp;</div>")
            }
            exportHTML()
        } else alert("Invalid row input");
        else alert("Invalid column input")
    });
    jQuery("#generate").trigger("click")
});

function selectCells(a, e) {
    for (var b = getCellCols(a), c = getCellRows(a), g = getCellCols(e), f = getCellRows(e), i = b.length, j = c.length, h = g.length, o = f.length, k = 100, l = 0, m = 100, n = 0, d = 0; d < i; d++) b[d] < k && (k = b[d]), b[d] > l && (l = b[d]);
    for (d = 0; d < h; d++) g[d] < k && (k = g[d]), g[d] > l && (l = g[d]);
    for (d = 0; d < j; d++) c[d] < m && (m = c[d]), c[d] > n && (n = c[d]);
    for (d = 0; d < o; d++) f[d] < m && (m = f[d]), f[d] > n && (n = f[d]);
    for (d = m; d <= n; d++) for (c = k; c <= l; c++) jQuery(".c" + c).filter(".r" + d).addClass("s");
    do {
        b = !1;
        f = jQuery(".s");
        i = f.size();
        g = [];
        c = [];
        for (d = 0; d < i; d++) g = g.concat(getCellCols(f.eq(d))),
        c = c.concat(getCellRows(f.eq(d)));
        d = Math.max.apply(Math, g);
        g = Math.min.apply(Math, g);
        f = Math.max.apply(Math, c);
        c = Math.min.apply(Math, c);
        d > l && (l = d, b = !0);
        g < k && (k = g, b = !0);
        f > n && (n = f, b = !0);
        c < m && (m = c, b = !0);
        if (b) for (d = m; d <= n; d++) for (c = k; c <= l; c++) jQuery(".c" + c).filter(".r" + d).addClass("s");
        else selectedColspan = l - k + 1, selectedRowspan = n - m + 1
    } while (b)
}
jQuery(function () {
    jQuery("td").live("mousedown", function (a) {
        1 === a.which && (RemoveSelection(), isMouseDown = !0, mouseDownCell = this)
    });
    jQuery("td").live("mousemove", function () {
        isMouseDown && mouseDownCell != this && (isDraggedBetweenCells = !0, RemoveSelection(), jQuery(".s").removeClass("s"), selectCells(mouseDownCell, this))
    });
    jQuery(document).on("mouseup", function () {
        isMouseDown && (isMouseDown = !1, mouseDownCell = void 0, isDraggedBetweenCells = !1)
    });
    jQuery("#tableWrap").on("mousedown", function (a) {
        1 === a.which && jQuery(".s").removeClass("s")
    })
});

function getLowestCol(a) {
    a = getCellCols(a);
    return Math.min.apply(Math, a)
}

function optimiseColspan() {
    var a, e, b = jQuery("tr"),
        c = b.size(),
        g, f, i, j, h = [];
    for (a = 0; a < cols; a++) h[a] = a + 1;
    for (e = 0; e < c; e++) {
        g = b.eq(e).children();
        f = g.size();
        for (a = 0; a < f; a++) i = getLowestCol(g.eq(a)), j = void 0 == g.eq(a).attr("colspan") ? 1 : parseInt(g.eq(a).attr("colspan"), 10), - 1 !== h.indexOf(i + j) && h.splice(h.indexOf(i + j), 1);
        if (1 > h.length) break
    }
    cols -= h.length;
    for (a = 0; a < h.length; a++) {
        b = ".c" + (h[a] - 1);
        $classArray = jQuery(b);
        $classArrayL = $classArray.size();
        for (c = 0; c < $classArrayL; c++) b = parseInt($classArray.eq(c).attr("colspan"),
        10), $classArray.eq(c).attr("colspan", b - 1)
    }
}
function optimiseRowspan() {
    for (var a = jQuery("tr:empty"), e = a.length, b, c, g, f, i = 0; i < e; i++) {
        b = jQuery("tr").index(a.eq(i));
        b = jQuery(".r" + b);
        c = b.length;
        for (f = 0; f < c; f++) g = b.eq(f).attr("rowspan") - 1, b.eq(f).attr("rowspan", g)
    }
    rows -= jQuery("tr:empty").size();
    jQuery("tr:empty").remove()
}

function mergeCells() {
    for (var a = jQuery(".s"), e = a.length, b = "", c = 0; c < e; c++) b += " " + a.eq(c).attr("class");
    b = b.replace(/s/gi, "");
    selectedColspan === cols && (rows = rows - selectedRowspan + 1, selectedRowspan = 1);
    a.eq(0).before("<td class='" + b + "' colspan='" + selectedColspan + "' rowspan='" + selectedRowspan + "'><div contenteditable='true'>&nbsp;</div>");
    a.remove();
    selectedColspan === cols && jQuery("tr:empty").remove();
    reindexTable();
    optimiseRowspan();
    optimiseColspan();
    reindexTable();
    exportHTML()
}
jQuery(function () {
    jQuery("#merge").on("click", function () {
        mergeCells()
    })
});
jQuery(function () {
    jQuery("div").live("blur", function () {
        exportHTML()
    });
    jQuery("td").live("click", function () {
        jQuery(this).children("div").focus()
    })
});