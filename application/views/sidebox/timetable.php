<h1><?= $category ?> Timetable</h1>
<?= form_open("admin/add_timetable") ?> 

Day<br/>   
<select name="day">
    <option value ="1" selected="selected">Monday</option>
    <option value ="2">Tuesday</option>
    <option value ="3">Wednesday</option>
    <option value ="4">Thursday</option>
    <option value ="5">Friday</option>
    <option value ="6">Saturday</option>
    <option value ="7">Sunday</option>
</select>


<br/>
Start Time</br>
<input  type="text" id="timepicker1" name="startTime" value="" /><br/>
End Time</br>
<input  type="text" id="timepicker2" name="endTime" value="" /><br/>

Class<br/>
<input  type="text" name="className" value="" />


Instructor<br/>
<input  type="text" name="instructor" value="" />


Level<br/>
<input  type="text" name="level" value="" />


Location<br/>
<input  type="text" name="location" value="" />
<input  type="text"  name="timetableCategory" value="<?= $category ?>" />
<input type="submit" />

<?= form_close() ?> 