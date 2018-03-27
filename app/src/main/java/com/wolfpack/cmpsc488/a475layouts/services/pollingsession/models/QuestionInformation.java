package com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models;

/**
 * Created by peo5032 on 3/24/18.
 */

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class QuestionInformation {

    @SerializedName("question_id")
    @Expose
    private String questionId;
    @SerializedName("teacher_id")
    @Expose
    private String teacherId;
    @SerializedName("question_type")
    @Expose
    private String questionType;
    @SerializedName("description")
    @Expose
    private String description;
    @SerializedName("potential_answers")
    @Expose
    private String potentialAnswers;
    @SerializedName("correct_answers")
    @Expose
    private String correctAnswers;

    public String getQuestionId() {
        return questionId;
    }

    public void setQuestionId(String questionId) {
        this.questionId = questionId;
    }

    public String getTeacherId() {
        return teacherId;
    }

    public void setTeacherId(String teacherId) {
        this.teacherId = teacherId;
    }

    public String getQuestionType() {
        return questionType;
    }

    public void setQuestionType(String questionType) {
        this.questionType = questionType;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getPotentialAnswers() {
        return potentialAnswers;
    }

    public void setPotentialAnswers(String potentialAnswers) {
        this.potentialAnswers = potentialAnswers;
    }

    public String getCorrectAnswers() {
        return correctAnswers;
    }

    public void setCorrectAnswers(String correctAnswers) {
        this.correctAnswers = correctAnswers;
    }

}

