Imports MySql.Data.MySqlClient
Imports Excel = Microsoft.Office.Interop.Excel
Module Module_Lib
    Private ReadOnly connStr As String = "Server=localhost;User ID=root;database=accounting_information_systems;"
    Private ReadOnly conn As New MySqlConnection(connStr)
    Private cmd As New MySqlCommand
    Private adt As New MySqlDataAdapter
    Private tables As New DataTable
    Private array As New ArrayList
    Public sql As String
    Public Sub koneksi()
        Try
            If conn.State <> ConnectionState.Open Then conn.Open()
        Catch ex As MySqlException
            MsgBox(ex.Message)
        End Try
    End Sub
    Public Function dbSelect(query As String) As DataTable
        Try
            koneksi()
            tables = New DataTable()
            cmd = New MySqlCommand(query, conn)
            adt = New MySqlDataAdapter(cmd)
            adt.Fill(tables)
            Return tables
        Catch ex As Exception
            MsgBox(query)
        End Try
    End Function
    Public Function releaseObject(ByVal obj As Object)
        Try
            System.Runtime.InteropServices.Marshal.ReleaseComObject(obj)
            obj = Nothing
        Catch ex As Exception
            obj = Nothing
        Finally
            GC.Collect()
        End Try
    End Function
    Public Sub createFormBarangBaru()
        Dim xlApp As Excel.Application = New Microsoft.Office.Interop.Excel.Application()

        If xlApp Is Nothing Then
            MessageBox.Show("Excel is not properly installed!!")
            Return
        End If
        Dim fields() As String = {"kode_barang", "barcode_1", "barcode_2", "barcode_3", "barcode_4", "barcode_5", "nama_barang", "harga_1", "satuan_1", "kali_1", "harga_2", "satuan_2", "kali_2", "harga_3", "satuan_3", "kali_3", "harga_4", "satuan_4", "kali_4", "harga_5", "satuan_5", "kali_5", "hpp", "stok_minimum", "ket"}
        sql = "SELECT replace(upper(column_name),'_',' ') FROM INFORMATION_SCHEMA.COLUMNS where table_schema='accounting_information_systems' and table_name='master_barang' and column_name in ('" & String.Join("','", fields) & "') "
        Dim dataDumm As String = "select " & String.Join(",", fields) & " FROM accounting_information_systems.`master_barang` WHERE id_barang='1' "

        Dim xlWorkBook As Excel.Workbook
        Dim xlWorkSheet As Excel.Worksheet
        Dim misValue As Object = System.Reflection.Missing.Value

        xlWorkBook = xlApp.Workbooks.Add(misValue)
        xlWorkSheet = xlWorkBook.Sheets("sheet1")
        tables = dbSelect(sql)

        For i = 0 To tables.Rows.Count() - 1
            xlWorkSheet.Cells(1, i + 1) = tables.Rows(i)(0)
        Next
        tables = dbSelect(dataDumm)
        For i = 0 To tables.Columns.Count - 1
            xlWorkSheet.Cells(2, i + 1) = tables.Rows(0)(i)
        Next

        Dim strPath As String
        strPath = Environ("HomeDrive") & Environ("HomePath") & "\Desktop\Form Barang Baru.xlsx"

        xlWorkBook.SaveAs(strPath)
        xlWorkBook.Close(True, misValue, misValue)
        xlApp.Quit()

        releaseObject(xlWorkSheet)
        releaseObject(xlWorkBook)
        releaseObject(xlApp)
    End Sub









End Module
