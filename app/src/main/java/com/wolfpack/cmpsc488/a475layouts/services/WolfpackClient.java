package com.wolfpack.cmpsc488.a475layouts.services;


import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.wolfpack.cmpsc488.a475layouts.services.authentication.LoginDetails;
import com.wolfpack.cmpsc488.a475layouts.services.data_retrieval.BasicWolfpackResponse;

import okhttp3.MultipartBody;
import okhttp3.OkHttpClient;
import okhttp3.ResponseBody;
import okhttp3.logging.HttpLoggingInterceptor;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.models.ClassListResult;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.models.ClassResult;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.models.SearchClassResult;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.models.SearchResultSection;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ActiveQuestionInfo;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ActiveSessionInfo;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.PollingResults;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ValidateQuestionInfo;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Body;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.Header;
import retrofit2.http.POST;

public interface WolfpackClient{

    //local testing for Pabz (pabz -> Pabz : you are a proper noun my friend)
    String BASE_URL = "http://192.168.1.57";

    //local testing for Tyler
    //String BASE_URL = "192.169.1.125";


    //Reference for converting JSON to POJO
    //http://www.jsonschema2pojo.org/

    //String BASE_URL = "http://wolfpack.cs.hbg.psu.edu";

    String FEED = "/lib/php/androidAPI.php";

    Gson gson = new GsonBuilder()
            .setLenient()
            .create();

    HttpLoggingInterceptor interceptor = new HttpLoggingInterceptor()
            .setLevel(HttpLoggingInterceptor.Level.BODY);

    OkHttpClient client = new OkHttpClient.Builder()
            .addInterceptor(interceptor).build();

    Retrofit debugRetrofit = new Retrofit.Builder()
            .client(client)
            .baseUrl(BASE_URL)
            .build();

    Retrofit retrofit = new Retrofit.Builder()
            .client(client)
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build();

    @FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails> attemptLoginStudent(
            @Field("inputMethodName") String methodName,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails> attemptLoginTeacher(
            @Field("inputMethodName") String methodName,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails> attemptSignUp(
            @Field("inputMethodName") String methodName,
            @Field("inputUserTitle") String inputTitle,
            @Field("inputFirstName") String first_name,
            @Field("inputLastName") String last_name,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password,
            @Field("inputConfirmPassword") String confirmPassword
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<SearchClassResult<SearchResultSection>> findClassesToAdd(
            @Field("inputClassTitle") String title,
            @Field("inputFirstName") String firstName,
            @Field("inputLastName") String lastName,
            @Field("inputCurrentPage") int currentPage,
            @Field("inputRowsPerPage") int rowsPerPage,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<ResponseBody> testFindClassesToAdd(@Field("inputClassTitle") String title,
                                            @Field("inputFirstName") String firstName,
                                            @Field("inputLastName") String lastName,
                                            @Field("inputCurrentPage") int currentPage,
                                            @Field("inputRowsPerPage") int rowsPerPage,
                                            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<ResponseBody> testEnrollInClass(
            @Field("inputStudentId") String student_id,
            @Field("inputClassId") String class_id,
            @Field("inputMethodName") String methodName
    );

    @POST(FEED)
    Call<BasicWolfpackResponse> uploadSinglePic(
            @Header("Content-Type") String contentType,
            @Header("Authorization") String auth,
            @Body MultipartBody body

    );

    @FormUrlEncoded
    @POST(FEED)
    Call<ClassListResult<ClassResult>> findEnrolledClasses(
            @Field("currentPage") int currentPage,
            @Field("rowsPerPage") int rowsPerPage,
            @Field("student_id") int student_id,
            //@Field("inputUserEmail") String email,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<BasicWolfpackResponse> enrollForClass(
            @Field("inputStudentId") String  student_id,
            @Field("inputClassId") String class_id,
            @Field("inputMethodName") String methodName
    );

    // Polling Calls
    @FormUrlEncoded
    @POST(FEED)
    Call<PollingResults<ActiveSessionInfo>> searchActiveSession(
            @Field("inputClassId") String inputClassId,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<ResponseBody> testActiveSession(
            @Field("inputClassId") String inputClassId,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<PollingResults<ActiveQuestionInfo>> searchActiveQuestion(
            @Field("inputQuestionSetId") String inputQuestionSetId,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<ResponseBody> testActiveQuestion(
            @Field("inputQuestionSetId") String  inputQuestionSetId,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<PollingResults<QuestionInformation>> searchLiveQuestionInfo(
            @Field("inputQuestionId") String  inputQuestionId,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<ResponseBody> testLiveQuestionInfo(
            @Field("inputQuestionId") String  inputQuestionId,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<BasicWolfpackResponse> submitAnswer(
            @Field("inputStudentId") String inputStudentId,
            @Field("inputSessionId") String  inputSessionId,
            @Field("inputQuestionHistoryId") String inputQuestionHistoryId,
            @Field("inputAnswerType") String inputAnswerType,
            @Field("inputAnswer") String inputAnswer,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<ResponseBody> testSubmitAnswer(
            @Field("inputStudentId") String inputStudentId,
            @Field("inputSessionId") String  inputSessionId,
            @Field("inputQuestionHistoryId") String inputQuestionHistoryId,
            @Field("inputAnswerType") String inputAnswerType,
            @Field("inputAnswer") String inputAnswer,
            @Field("inputMethodName") String methodName
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<PollingResults<ValidateQuestionInfo>> validateSameQuestion(
            @Field("inputQuestionSetId") String  inputQuestionSetId,
            @Field("inputQuestionId") String  inputQuestionId,
            @Field("inputQuestionSessionId") String inputQuestionSessionId,
            @Field("inputQuestionHistoryId") String inputQuestionHistoryId,
            @Field("inputMethodName") String methodName
    );


}