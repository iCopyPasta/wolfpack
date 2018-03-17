package com.wolfpack.cmpsc488.a475layouts.services.data_retrieval;

import com.google.gson.annotations.SerializedName;

/**
 * Created by peo5032 on 3/9/18.
 */

public class BasicWolfpackResponse {
    @SerializedName("success")
    private int status;
    @SerializedName("message")
    private String message;

    BasicWolfpackResponse(int status, String message){
        this.status = status;
        this.message = message;
    }

    BasicWolfpackResponse(){
        this.status = 0;
        this.message = "None";

    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    @Override
    public String toString(){
        return "JSON OBJ: message = " + this.getMessage()
                + " status = " + this.getStatus();
    }
}
