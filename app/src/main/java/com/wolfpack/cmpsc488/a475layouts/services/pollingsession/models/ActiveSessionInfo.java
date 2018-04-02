package com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models;

/**
 * Created by peo5032 on 3/24/18.
 */

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class ActiveSessionInfo {


    @SerializedName("question_set_id")
    @Expose
    private String questionSetId;
    @SerializedName("question_session_id")
    @Expose
    private String questionSessionId;
    @SerializedName("question_set_name")
    @Expose
    private String questionSetName;

    public String getQuestionSetId() {
        return questionSetId;
    }

    public void setQuestionSetId(String questionSetId) {
        this.questionSetId = questionSetId;
    }

    public String getQuestionSessionId() {
        return questionSessionId;
    }

    public void setQuestionSessionId(String questionSessionId) {
        this.questionSessionId = questionSessionId;
    }

    public String getQuestionSetName() {
        return questionSetName;
    }

    public void setQuestionSetName(String questionSetName) {
        this.questionSetName = questionSetName;
    }

}