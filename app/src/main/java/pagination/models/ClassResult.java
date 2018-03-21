package pagination.models;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class ClassResult {

    @SerializedName("course_instructor")
    @Expose
    private String courseInstructor;
    @SerializedName("title")
    @Expose
    private String courseDescription;

    public String getCourseInstructor() {
        return courseInstructor;
    }

    public void setCourseInstructor(String courseInstructor) {
        this.courseInstructor = courseInstructor;
    }

    public String getCourseDescription() {
        return courseDescription;
    }

    public void setCourseDescription(String courseDescription) {
        this.courseDescription = courseDescription;
    }

    @Override
    public String toString(){
        return "instructor: " + courseInstructor +
                ", course: " + courseDescription;
    }

}