package com.wolfpack.cmpsc488.a475layouts.services;


import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.wolfpack.cmpsc488.a475layouts.services.authentication.LoginDetails;
import com.wolfpack.cmpsc488.a475layouts.services.data_retrieval.BasicWolfpackResponse;

import okhttp3.MultipartBody;
import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import pagination.models.ClassListResult;
import pagination.models.ClassResult;
import pagination.models.SearchClassResult;
import pagination.models.SearchResultSection;
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
    Call<SearchClassResult<SearchResultSection>> findClasses(
            @Field("inputCurrentPageNumber") int currentPage,
            @Field("inputClassTitle") String classTitle,
            @Field("inputMethodName") String methodName
    );

    @POST(FEED)
    Call<BasicWolfpackResponse> uploadSinglePic(
            @Header("Content-Type") String contentType,
            @Header("Authorization") String auth,
            @Body MultipartBody body

    );

    //Change to your JSON response
    @FormUrlEncoded
    @POST(FEED)
    Call<ClassListResult<ClassResult>> findEnrolledClasses(
            @Field("inputCurrentPageNumber") int currentPage,
            @Field("inputUserEmail") String email,
            @Field("inputMethodName") String methodName
    );

}