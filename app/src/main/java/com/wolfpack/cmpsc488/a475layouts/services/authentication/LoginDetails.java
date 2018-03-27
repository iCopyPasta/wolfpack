package com.wolfpack.cmpsc488.a475layouts.services.authentication;

import com.google.gson.annotations.SerializedName;
import com.google.gson.annotations.Expose;


/**
 * Created by pablo on 2/19/18.
 *
 */

public class LoginDetails {

    @SerializedName("message")
    @Expose
    private String message;
    @SerializedName("success")
    @Expose
    private Integer success;
    @SerializedName("student_id")
    @Expose
    private String studentId;

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public Integer getSuccess() {
        return success;
    }

    public void setSuccess(Integer success) {
        this.success = success;
    }

    public String getStudentId() {
        return studentId;
    }

    @Override
    public String toString(){
        return "JSON OBJ: message = " + this.getMessage()
                + " status = " + this.getSuccess() + " student_id = " + getStudentId();
    }


}
