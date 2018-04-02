package com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models;

/**
 * Created by peo5032 on 3/24/18.
 */

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class ActiveQuestionInfo {

    @SerializedName("question_id")
    @Expose
    private String questionId;
    @SerializedName("question_history_id")
    @Expose
    private String questionHistoryId;

    public String getQuestionId() {
        return questionId;
    }

    public void setQuestionId(String questionId) {
        this.questionId = questionId;
    }

    public String getQuestionHistoryId() {
        return questionHistoryId;
    }

    public void setQuestionHistoryId(String questionHistoryId) {
        this.questionHistoryId = questionHistoryId;
    }

}