Imports Excel = Microsoft.Office.Interop.Excel
Public Class exportExcelBarang
    Private Sub BT_Export_Click(sender As Object, e As EventArgs) Handles BT_Export.Click
        createFormBarangBaru()
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim objXLApp As Excel.Application
        Dim intLoopCounter As Integer
        Dim objXLWb As Excel.Workbook
        Dim objXLWs As Excel.Worksheet
        Dim objRange As Excel.Range
        Dim str As String = ""
        objXLApp = New Excel.Application
        Dim strPath As String
        strPath = Environ("HomeDrive") & Environ("HomePath") & "\Desktop\Form Barang Baru.xlsx"
        objXLApp.Workbooks.Open(strPath)
        objXLWb = objXLApp.Workbooks(1)
        objXLWs = objXLWb.Worksheets(1)
        For intLoopCounter = 1 To CInt(objXLWs.Cells.SpecialCells(Excel.XlCellType.xlCellTypeLastCell).Row)
            objRange = objXLWs.Range("A" & intLoopCounter, "Z" & intLoopCounter)
            For Each item As String In objRange.Value
                str &= item
            Next
            MsgBox(str)
        Next intLoopCounter

        objXLApp.Quit()
    End Sub
End Class